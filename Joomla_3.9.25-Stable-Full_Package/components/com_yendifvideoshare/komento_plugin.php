<?php 
/*
 * Komento comments plugin for Yendif Video Share
 *
 * @version		$Id: komento_plugin.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

if( file_exists( JPATH_ROOT.'/components/com_komento/komento_plugins/abstract.php' ) ) {
	require_once JPATH_ROOT.'/components/com_komento/komento_plugins/abstract.php';
}

require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_yendifvideoshare'.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'utils.php' );

class KomentoComYendifVideoShare extends KomentoExtension	{

	public $_item;

	public $_map = array(
		'id'         => 'id',
		'title'      => 'title',
		'hits'       => 'views',
		'created_by' => 'userid',
		'catid'      => 'catid',
		'permalink'  => 'permalink_field'
	);

	public function __construct( $component ) {
		parent::__construct( $component );
	}
	
	public function load( $cid ) {			
		static $instances = array();	
			
		if( ! isset( $instances[ $cid ] ) ) {	
			$app = JFactory::getApplication();	
			$db = JFactory::getDbo();
			
			$cid = (int) $cid;
			
			$query = "SELECT * FROM #__yendifvideoshare_videos WHERE id=".$cid;			
			$db->setQuery( $query );
			$this->_item = $db->loadObject();	
	  
			$this->_item->permalink_field = YendifVideoShareUtils::buildRoute( $this->_item, 'video' );	  
			$instances[ $cid ] = $this->_item;															
		}		
		
		$this->_item = $instances[ $cid ];
		
		return $this;
		
	}
	
	public function getContentId() {
		return $this->_item->{$this->_map['id']};
	}
	
	public function getContentTitle() {
		return $this->_item->{$this->_map['title']};
	}
	
	public function isListingView() {
		$app = JFactory::getApplication();	
		$views = array('videos', 'categories' );
				
		return in_array( $app->input->get('view'), $views );		
	}

	public function isEntryView() {
		$app = JFactory::getApplication();
		return $app->input->get('view') == 'video';
	}

	public function onExecute( &$article, $html, $view, $options = array() ) {
		$config = Komento::getConfig( 'com_yendifvideoshare' );
		$model = Komento::getModel('comments');
		$count = $model->getCount($this->component, $this->getContentId());
		$article->numOfComments = $count;		
			
		return $html;
	}
	
	public function getComponentIcon() {
		 return '../media/yendifvideoshare/assets/admin/images/yendifvideoshare_logo.ico';
	}
 
	public function getComponentName() {
		 return 'Yendif Video Share';
	}

	public function getContentHits() {
		return $this->_item->{$this->_map['hits']};
	}

	public function getAuthorId() {
		return $this->_item->{$this->_map['created_by']};
	}
	
	public function getCategories(){	      			
		$categories = array();
		
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM #__yendifvideoshare_categories ORDER BY ordering ASC';
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();
		
		$children = array();
		if( $mitems ) {
			foreach( $mitems as $v ) {
				$v->title = $v->name;
				$v->parent_id = $v->parent;
				$pt = $v->parent;				
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}
		
		$categories = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);					
		
		foreach( $categories as $row ) {											
			$row->treename =JString::str_ireplace('&#160;', '.&#160;&#160;&#160;', $row->treename);			
			$row->treename = str_replace('-', '|_', $row->treename);		
		}				
		
		return $categories;
	}
	
	public static function getCommentCounts( $cid ) {				
		$db = JFactory::getDbo();
		$query = 'SELECT COUNT(*) FROM #__komento_comments WHERE cid='.$cid.' AND component='.$db->quote('com_yendifvideoshare').' AND published=1';
		$db->setQuery( $query );
					
		return $db->loadResult();
	}
	
	 public function getContentIds( $categories = '' ) {	 
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id, a.title, a.catid');
		$query->from($db->quoteName('#__yendifvideoshare_videos').' AS a');
		$query->join('LEFT', '#__yendifvideoshare_categories AS c ON c.id = a.catid');		
		
		if( ! empty( $categories ) ) {
			if( is_array( $categories ) ) {
				$categories = implode( ',', $categories );
			}
			$query->where('a.catid IN '.$db->quote($categories));
		}
	 
		$db->setQuery( (string) $query );	
		
		return $db->loadResultArray();	 				
	}
	
}