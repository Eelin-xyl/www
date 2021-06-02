<?php

/*
 * @version		$Id: fields.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies PVT Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareFields {
	
	public static function RadioGroup( $name, $items, $selected = '', $script = '' ) {
	
		$options = array();
		
		foreach( $items as $key => $value ) {
			$options[] = JHTML::_('select.option', $key, $value);
		}
		
		return JHTML::_('select.radiolist', $options, $name, $script, 'value', 'text', $selected);
		
	}
	
	public static function ListItems( $name, $items, $selected = '', $script = '' ) {
	
		$options = array();
		
		foreach( $items as $key => $value ) {
			$options[] = JHTML::_('select.option', $key, $value);
		}
		
		return JHTML::_('select.genericlist', $options, $name, $script, 'value', 'text', $selected);
				
	}
	
	public static function ListBoolean( $name, $selected = 1, $script = '' ) {		
	
		$options[] = JHTML::_('select.option', 1, JText::_('YENDIF_VIDEO_SHARE_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('YENDIF_VIDEO_SHARE_NO'));
		
		return JHTML::_('select.genericlist', $options, $name, $script, 'value', 'text', $selected);
				
	}
	
	public static function ListSefOptions( $name, $selected = 0, $script = '' ) {	
			
		$options = array();
		
		if( $name == "sef_video" ) {
			$options[] = JHTML::_('select.option', 0, JText::_('YENDIF_VIDEO_SHARE_SEF_VIDEO_URL') );	
			$options[] = JHTML::_('select.option', 1, JText::_('YENDIF_VIDEO_SHARE_SEF_VIDEO_CATEGORY_URL') );		
		} else if( $name == "sef_sptr" ) {
			$options[] = JHTML::_('select.option', 0, JText::_('YENDIF_VIDEO_SHARE_SEF_USE_A_DASH') );	
			$options[] = JHTML::_('select.option', 1, JText::_('YENDIF_VIDEO_SHARE_SEF_USE_A_SLASH') );
		} else {
			$options[] = JHTML::_('select.option', 0, JText::_('YENDIF_VIDEO_SHARE_SEF_ID_BEFORE_TITLE') );	
			$options[] = JHTML::_('select.option', 1, JText::_('YENDIF_VIDEO_SHARE_SEF_ID_AFTER_TITLE') );
		}
				
		return JHTML::_('select.genericlist', $options, $name, $script, 'value', 'text', $selected);
				
	}	
	
	public static function ListDisplay( $name, $selected = 1, $script = '' ) {	
		
		$options[] = JHTML::_('select.option', 1, JText::_('YENDIF_VIDEO_SHARE_SHOW'));
		$options[] = JHTML::_('select.option', 0, JText::_('YENDIF_VIDEO_SHARE_HIDE'));
		
		return JHTML::_('select.genericlist', $options, $name, $script, 'value', 'text', $selected);	
			
	}
	
	public static function ListCategories( $name, $items, $selected = -1, $script = '' ) {
	
		$options[] = JHTML::_('select.option', -1, JText::_('YENDIF_VIDEO_SHARE_SELECT_CATEGORY'));
		
		foreach( $items as $item ) {
			$item->treename = JString::str_ireplace('&#160;', '-', $item->treename);
			$options[] = JHTML::_('select.option', $item->id, $item->treename );
		}
		
		return JHTML::_('select.genericlist', $options, $name, $script, 'value', 'text', $selected);
				
	}
	
	public static function ListPreroll( $name, $selected = -1, $script = '' ) {	
		
		$options[] = JHTML::_('select.option', 0, '-- '.JText::_('YENDIF_VIDEO_SHARE_NONE').' --');
		$options[] = JHTML::_('select.option', -1, '-- '.JText::_('YENDIF_VIDEO_SHARE_DEFAULT').' --');
		
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM #__yendifvideoshare_adverts WHERE published=1 AND type!='.$db->quote('postroll');
		$db->setQuery( $query );
		$items = $db->loadObjectList();
		
		foreach( $items as $item ) {
			$options[] = JHTML::_('select.option', $item->id, $item->title );
		}
		
		return JHTML::_('select.genericlist', $options, $name, $script, 'value', 'text', $selected);
				
	}
	
	public static function ListPostroll( $name, $selected = -1, $script = '' ) {	
		
		$options[] = JHTML::_('select.option', 0, '-- '.JText::_('YENDIF_VIDEO_SHARE_NONE').' --');
		$options[] = JHTML::_('select.option', -1, '-- '.JText::_('YENDIF_VIDEO_SHARE_DEFAULT').' --');		
		
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM #__yendifvideoshare_adverts WHERE published=1 AND type!='.$db->quote('preroll');
		$db->setQuery( $query );
		$items = $db->loadObjectList();
		
		foreach( $items as $item ) {
			$options[] = JHTML::_('select.option', $item->id, $item->title );
		}
		
		return JHTML::_('select.genericlist', $options, $name, $script, 'value', 'text', $selected);	
			
	}
	
	public static function MediaTypes( $name, $items, $selected = '', $script = '' ) {
	
		$options = array();
		
		foreach ( $items as $key => $value ) {
			$options[] = JHTML::_('select.option', $key, $value );
		}
		
		$options[] = JHTML::_( 'select.optgroup', '--- PRO Version ---' );
		$options[] = JHTML::_( 'select.option', 'pro_only', JText::_( 'YENDIF_VIDEO_SHARE_THIRD_PARTY' ) );
		
		return JHTML::_('select.genericlist', $options, $name, $script, 'value', 'text', $selected);	
		
	}
	
	public static function FancyEditor( $name = '', $value = '' ) {
	
		$editor = JFactory::getEditor();
		$params = array('mode'=> 'advanced');
		
		return $editor->display($name, $value, '350', '175', '20', '20', 1, null, null, null, $params);
		
	}
	
	public static function FileUploader( $name, $value = '', $allow_upload = 1 ) {
	
		$html = '';
		
		$types = array(
			'mp4'      => 'video/mp4,video/x-flv,video/x-m4v,video/m4v,video/quicktime',
			'mp4_hd'   => 'video/mp4,video/x-m4v,video/m4v,video/quicktime',
			'webm'     => 'video/webm',
			'ogg'      => 'video/ogg',
			'mobile'   => 'video/mp4,video/x-m4v,video/m4v,video/quicktime,application/x-mpegURL',
			'image'    => 'image/*',
			'logo'     => 'image/*',
			'default_image'     => 'image/*',
			'feed_icon'     => 'image/*',			
			'captions' => 'text/vtt'
		);
		
		if( $name == 'mp4_hd' ) {
			$class = 'validate-hd yendif-margin-right';
		} else {		
			$class = 'validate-'.$name.' yendif-margin-right';
		}
		
		if( $name == 'mp4' ) {
			$class .= ' yendif-media-required';
		}

		if( $allow_upload ) {
			$html .= '<div class="yendif-media-uploader-widget">';
			$html .= '<div class="yendif-block yendif-margin-bottom">';
			$html .= '<label class="radio inline"><input type="radio" name="method_'.$name.'" value="url">'.JText::_('YENDIF_VIDEO_SHARE_DIRECT_URL').'</label>';
			$html .= '<label class="radio inline"><input type="radio" name="method_'.$name.'" value="upload" checked>'.JText::_('YENDIF_VIDEO_SHARE_UPLOAD_FROM_DRIVE').'</label>';
			$html .= '</div>';
			$html .= '<div class="yendif-block yendif-margin-bottom">';
			$html .= '<input type="text" name="'.$name.'" id="'.$name.'" class="'.$class.'" placeholder="'.JText::_('YENDIF_VIDEO_SHARE_ADD_MEDIA_PLACEHOLDER').'" value="'.$value.'"/>';
			$html .= '<button type="button" id="yendif-browse-btn-'.$name.'" class="btn btn-success yendif-browse-btn" data-field="'.$name.'" data-accept="'.$types[ $name ].'">'.JText::_('YENDIF_VIDEO_SHARE_BROWSE').'</button>';
			$html .= '<span id="yendif-upload-response-'.$name.'" class="help-inline text-error"></span>';
			$html .= '</div>';
			$html .= '</div>';
		} else {
			$html .= '<input type="text" name="'.$name.'" id="'.$name.'" class="'.$class.'" placeholder="'.JText::_('YENDIF_VIDEO_SHARE_ADD_MEDIA_PLACEHOLDER').'" value="'.$value.'" />';
		}

		return $html;	
		
	}
	
	public static function ListAdsType($name, $items, $selected = ''){
		$html = '';	
		foreach($items as $key => $value) {
			$checked = ( $key == $selected ) ? ' checked ' : '';
			
			$html .= '<label class="yendifLabel" for="' . ( $name . '_' . $key ). '" style="display: inline-block;margin-right: 15px;">';	
			$html .= '<input 
						type="radio"
						name="' . $name . '"
						class="yendifRadio"
						id="' . ( $name . '_' . $key ) . '"
						value="' . $key . '"' . $checked .
						'onchange="yendifChangeAdsType(\''. $key .'\')" />';
			$html .= '<span style="padding:5px;margin: 3px;">' . $value . '</span></label>';
		}
		
		return $html;		
	}

	
}