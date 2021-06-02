<?php

/*
 * @version		$Id: yendifvideoshare.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Yendif Video Share Search plugin.
 *
 * @since  1.2.6
 */
class PlgSearchYendifVideoShare extends JPlugin {

	/**
	 * Determine areas searchable by this plugin.
	 *
	 * @return  array  An array of search areas.
	 *
	 * @since   1.2.6
	 */
	public function onContentSearchAreas() {
	
		static $areas = array(
			'videos' => 'Videos'
		);

		return $areas;
		
	}

	/**
	 * Search Videos.
	 * The SQL must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav.
	 *
	 * @param   string  $text      Target search string.
	 * @param   string  $phrase    Matching option (possible values: exact|any|all).  Default is "any".
	 * @param   string  $ordering  Ordering option (possible values: newest|oldest|popular|alpha|category).  Default is "newest".
	 * @param   mixed   $areas     An array if the search it to be restricted to areas or null to search all areas.
	 *
	 * @return  array              Search results.
	 *
	 * @since   1.2.6
	 */
	public function onContentSearch( $text, $phrase = '', $ordering = '', $areas = null ) {
	
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();

		JLoader::register( 'SearchHelper', JPATH_ADMINISTRATOR . '/components/com_search/helpers/search.php' );
		JLoader::register( 'YendifVideoShareUtils', JPATH_ADMINISTRATOR . '/components/com_yendifvideoshare/libraries/utils.php' );

		$searchText = $text;

		if( is_array( $areas ) ) {
			if( ! array_intersect( $areas, array_keys( $this->onContentSearchAreas() ) ) ) {
				return array();
			}
		}

		$limit     = $this->params->def( 'search_limit', 50 );

		$nullDate  = $db->getNullDate();
		$date      = JFactory::getDate();
		$now       = $date->toSql();

		$text = trim( $text );

		if( $text == '' ) {
			return array();
		}

		switch( $phrase ) {
			case 'exact':
				$text      = $db->quote( '%' . $db->escape( $text, true ) . '%', false );
				$wheres2   = array();
				$wheres2[] = 'v.title LIKE ' . $text;
				$wheres2[] = 'v.description LIKE ' . $text;
				$wheres2[] = 'v.meta_keywords LIKE ' . $text;
				$wheres2[] = 'v.meta_description LIKE ' . $text;
				$where     = '(' . implode( ') OR (', $wheres2 ) . ')';
				break;

			case 'all':
			case 'any':
			default:
				$words = explode( ' ', $text );
				$wheres = array();

				foreach( $words as $word ) {
					$word      = $db->quote( '%' . $db->escape( $word, true ) . '%', false );
					$wheres2   = array();
					$wheres2[] = 'LOWER(v.title) LIKE LOWER(' . $word . ')';
					$wheres2[] = 'LOWER(v.description) LIKE LOWER(' . $word . ')';
					$wheres2[] = 'LOWER(v.meta_keywords) LIKE LOWER(' . $word . ')';
					$wheres2[] = 'LOWER(v.meta_description) LIKE LOWER(' . $word . ')';
					$wheres[]  = implode( ' OR ', $wheres2 );
				}

				$where = '(' . implode( ( $phrase == 'all' ? ') AND (' : ') OR (' ), $wheres ) . ')';
				break;
		}

		switch( $ordering )	{
			case 'oldest':
				$order = 'v.created_date ASC';
				break;

			case 'popular':
				$order = 'v.views DESC';
				break;

			case 'alpha':
				$order = 'v.title ASC';
				break;

			case 'category':
				$order = 'c.title ASC, v.title ASC';
				break;

			case 'newest':
			default:
				$order = 'v.created_date DESC';
				break;
		}

		$rows = array();
		$query = $db->getQuery( true );

		// Search Videos
		if( $limit > 0 ) {
			$query->clear();

			// SQLSRV changes.
			$case_when  = ' CASE WHEN ';
			$case_when .= $query->charLength( 'v.alias', '!=', '0' );
			$case_when .= ' THEN ';
			$v_id       = $query->castAsChar( 'v.id' );
			$case_when .= $query->concatenate( array( $v_id, 'v.alias' ), ':' );
			$case_when .= ' ELSE ';
			$case_when .= $v_id . ' END as slug';

			$case_when1  = ' CASE WHEN ';
			$case_when1 .= $query->charLength( 'c.alias', '!=', '0' );
			$case_when1 .= ' THEN ';
			$c_id = $query->castAsChar( 'c.id' );
			$case_when1 .= $query->concatenate( array( $c_id, 'c.alias' ), ':' );
			$case_when1 .= ' ELSE ';
			$case_when1 .= $c_id . ' END as catslug';

			$query->select( 'v.id, v.title AS title, v.alias AS alias, v.description AS text, v.meta_description as metadesc, v.meta_keywords as metakey, v.created_date AS created, v.catid' )
				->select( 'c.name AS section, ' . $case_when . ',' . $case_when1 . ', ' . '\'2\' AS browsernav' )

				->from( '#__yendifvideoshare_videos AS v' )
				->join( 'INNER', '#__yendifvideoshare_categories AS c ON c.id=v.catid' )
				->where(
					'(' . $where . ') AND v.published=1 AND c.published=1 '
						. 'AND (v.published_up = ' . $db->quote( $nullDate ) . ' OR v.published_up <= ' . $db->quote( $now ) . ') '
						. 'AND (v.published_down = ' . $db->quote( $nullDate ) . ' OR v.published_down >= ' . $db->quote( $now ) . ')'
				)
				->group( 'v.id' )
				->order($order);

			$db->setQuery( $query, 0, $limit );
				
			try	{
				$list = $db->loadObjectList();
			} catch( RuntimeException $e ) {
				$list = array();
				$app->enqueueMessage( JText::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error' );
			}
			
			$limit -= count( $list );

			if( isset( $list ) ) {
				foreach( $list as $key => $item ) {
					$list[ $key ]->href = YendifVideoShareUtils::buildRoute( $item, 'video' );
				}
			}

			$rows[] = $list;
		}

		$results = array();

		if( count( $rows ) ) {
			foreach( $rows as $row ) {
				$new_row = array();

				foreach( $row as $video ) {
					if( SearchHelper::checkNoHtml( $video, $searchText, array( 'text', 'title', 'metakey', 'metadesc' ) ) ) {
						$new_row[] = $video;
					}
				}

				$results = array_merge( $results, (array) $new_row );
			}
		}

		return $results;
		
	}
	
}
