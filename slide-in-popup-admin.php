<?php
/*

Copyright (C) <2009>  <Dragan Vuletic>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.opensource.org/licenses/gpl-3.0.html>


*/

if (!class_exists('TwobuySlideInPopupAdmin')) {
	
	class TwobuySlideInPopupAdmin {
		public $abs_site_root;
		public $site_http_root;
		
        function TwobuySlideInPopupAdmin() {
         	$this->site_http_root = get_option('siteurl');
         	//Root dir of the current site 
         	$this->abs_site_root= ABSPATH;         	
        }
        
        /**
         * Global options page
         *
         */
        function TwobuySlideInPopupAdmin_Make_Admin_Page() {
        	
        	if ( !current_user_can('manage_options') )
				return;
				
			$o = TwobuySlideInPopup::TwobuySlideInPopup_Get_Options();
			// Process the form
			if ( $_POST ) {	

				$fields = array(
					'box',
					'left',
					'right',
					'close',
					'delay',
					'coockie'
				);
				
				foreach ((array) $fields as $key) {
					$field = $_POST['slidein'][$key];
					if (!is_null($field) && $field != "") {
						$o[0][$key] = $field;
					} else {
						unset($o[0][$key]);
					}
				}
				update_option('slide_in_popup', $o);
				echo '<div class="updated fade">' . "\n"
						. '<p>'
						. '<strong>'
						. __('Settings saved.')
						. '</strong>'
						. '</p>' . "\n"
						. '</div>' . "\n";
						
			}
			
			$o = TwobuySlideInPopup::TwobuySlideInPopup_Get_Options();

        	// it's admin side, so options are better place
        	$sidebars_widgets = get_option('sidebars_widgets', array());
        	$sidebarT = $sidebars_widgets["slide_in_templates"];
        	$widgetsT = get_option('widget_slideins');
			// get all SlideIn widgets inside SlideIn Boxes sidebar
        	$wids = array();
        	$wnames = array();
        	foreach ((array) $sidebarT as $sw) {
        		if (preg_match('/^slideins-(\d+)$/',$sw,$matches) > 0) {
        			$wids[] = $matches[1];
        			$wnames[] = $widgetsT[$matches[1]]['title'];
        		}
        	}
        	
        	echo '<div class="wrap">' . "\n"
			. '<form method="post" action="">' . "\n";
			echo '<h2>'
			. __('Global setup for SlideIn Box')
			. '</h2>' . "\n";
			echo '<p>You can setup the global SlideIn settings here. Still, on each page/post you will be able to override these settings, either to disable the SlideIn for that page/post or to choose different box and/or settings. All archives pages, eg. tags/categories, will apply only global settings, you can not override global settings for them.</p>'."\n";
			echo '<table style="width: 100%; border-collapse: collapse; padding: 2px 0px; spacing: 2px 0px;">';
        	echo '<tr valign="top">' . "\n"
        		. '<td width="15%"  style="padding:15px 0;">'
				. '<label for="slidein[box]">Global SlideIn Box:</label> '
				. '</td>' . "\n"
				. '<td width="25%"  style="padding:15px 0;">'
				. '<select name="slidein[box]">'
				. '<option value="">Select the SlideIn Box</option>';
			foreach ($wids as $k => $v) {
				echo '<option '.($v==$o[0]['box']?'selected':'').' value="'.$v.'">'.$wnames[$k].'</option>';
			}
			echo '</select>'
				. '</td>'
				. '<td width="59%" style="padding:15px 0;">'
				. 'The selection of all "SlideIn Box" widgets in "SlideIn Boxes" sidebar.<br/>If you leave this unselected, you won\'t have globally set SlideIn Box, but other options from this page may still be used as globals.'
				. '</td>' . "\n"
        		. '</tr>'."\n";
        	echo '<tr valign="top">' . "\n"
        		. '<td width="15%" style="padding:15px 0;">'
				. 'Left side distance: '
				. '</td>' . "\n"
				. '<td width="25%" style="padding:15px 0;">'
				. '<input type="text" name="slidein[left]" size="6" value="'.(isset($o[0]['left'])?$o[0]['left']:'').'"/> Example "200px" or "40%"'
				. '</td>' . "\n"
				. '<td width="59%" rowspan="2" style="padding:15px 0;">'
				. 'Set these both to 0 to have your SlideIn Box spread across 100% screen width.<br/>Clear the field content to have that option not applied, eg "if Left is blank "left: Npx;" will not be applied at all. You can distance the SlideIn Box only from one edge this way, and have the width set inside chosen widget.'
				. '</td>'
        		. '</tr>'."\n";
        	echo '<tr valign="top">' . "\n"
        		. '<td width="15%" style="padding:0;">'
				. 'Right side distance: '
				. '</td>' . "\n"
				. '<td width="25%" style="padding:0;">'
				. '<input type="text" name="slidein[right]" size="6" value="'.(isset($o[0]['right'])?$o[0]['right']:'').'"/> Example "200px" or "40%"'
				. '</td>'
				//. '<td width="59%">'
				//. '<p>Set these to 0 to have your SlideIn Box spread across 100% screen width.<br/>Clear the field content to have that option not applied, eg "if Left is blank "left: Npx;" will not be applied at all. You can distance the SlideIn Box only from one edge this way, and have the width set inside chosen widget.</p>'
				//. '</td>' . "\n"
        		. '</tr>'."\n";
        	echo '<tr valign="top">' . "\n"
        		. '<td width="15%" style="padding:15px 0;">'
				. 'Delay: '
				. '</td>' . "\n"
				. '<td width="25%" style="padding:15px 0;">'
				. '<input type="text" name="slidein[delay]" size="3" value="'.(isset($o[0]['delay'])?$o[0]['delay']:'').'"/> seconds'
				. '</td>'
				. '<td width="59%" style="padding:15px 0;">'
				. 'Number of seconds SlideIn Box will wait, after loading the page, to show it\'s content.'
				. '</td>' . "\n"
        		. '</tr>'."\n";
        	echo '<tr valign="top">' . "\n"
        		. '<td width="15%" style="padding:15px 0;">'
				. 'Close policy: '
				. '</td>' . "\n"
				. '<td width="25%" style="padding:15px 0;">'
				. '<input type="radio" name="slidein[close]" value="close" '.(isset($o[0]['close']) && $o[0]['close'] == 'close'?'checked':'').'/> Close button '
				. '<br/><input type="radio" name="slidein[close]" value="toggle" '.(isset($o[0]['close']) && $o[0]['close'] == 'toggle'?'checked':'').'/> Toggle button'
				. '<br/><input type="radio" name="slidein[close]" value="none" '.((isset($o[0]['close']) && $o[0]['close'] == 'none') || !isset($o[0]['close'])?'checked':'').'/> None (default)'
				. '</td>'
				. '<td width="59%" style="padding:15px 0;">'
				. 'Close button, user can only close the SlideIn box, can not open it again without page refresh.
				<br/>Toggle button, user can toggle the display of SlideIn box by clicking on this button.
				<br/>None, either to disable the removal of SlideIn box by user, or to specify your own HTML for close button inside the widget, toggle button will not make any sense since it will be invisible once you close the SlideIn box.
				<br/>You must use thes class for close button inside widget:
				<br/>class="slidein_custom_close"'
				. '</td>' . "\n"
        		. '</tr>'."\n";
        	echo '<tr valign="top">' . "\n"
        		. '<td width="15%" style="padding:15px 0;">'
				. 'Coockie conditions: '
				. '</td>' . "\n"
				. '<td width="25%" style="padding:15px 0;">'
				. '<input type="radio" name="slidein[coockie]" value="none" '.((isset($o[0]['coockie']) && $o[0]['coockie'] == 'none') || !isset($o[0]['coockie'])?'checked':'').'/> Show every time page is visited (default)'
				. '<br/><input type="radio" name="slidein[coockie]" value="session" '.(isset($o[0]['coockie']) && $o[0]['coockie'] == 'session'?'checked':'').'/> Only show once per page per visit'
				. '<br/><input type="radio" name="slidein[coockie]" value="coockie" '.(isset($o[0]['coockie']) && $o[0]['coockie'] == 'coockie'?'checked':'').'/> Only show once per page on first visit'
				. '</td>'
				. '<td width="59%" style="padding:15px 0;">'
				. 'This controls how often your SlideIn boxes will be displayed to your visitors.
				<br/>First option will display the SlideIn Box each time user visit the page/post/archive pages on your site.
				<br/>Second option will display each defined SlideIn box only once per session. One session is time before user restart his browser. All archives pages are using the global settings, so if somebody visit one category page and get the SlideIn box, he will not see another box on other category/tag pages during that session.
				<br/>Third option will display each defined SlideIn box only once, and drop the coockie inside user browser. User will not see the SlideIn box again until coockie is expired ( 30 days ), or until you switch this setting to something else.
				<br/>You can override this setting for each page/post.'
				. '</td>' . "\n"
        		. '</tr>'."\n";
        	echo '</table>' . "\n";
        	echo '<p class="submit">'
			. '<input type="submit"'
				. ' value="' . esc_attr(__('Save Changes')) . '"'
				. ' />'
			. '</p>' . "\n";
			echo '</form>' . "\n"
			. '</div>' . "\n";
        	
        }
        
        /**
         * Save the options on each page/post
         *
         * @param unknown_type $post_id
         */
        function TwobuySlideInPopupAdmin_Save_Entry($post_id) {
        	
        	if ( !isset($_POST['slidein']) || wp_is_post_revision($post_id) || !current_user_can('edit_post', $post_id) ) {
				return;
        	}
        	
			if ( current_user_can('unfiltered_html') && current_user_can('edit_post', $post_id) ) {
				$o = TwobuySlideInPopup::TwobuySlideInPopup_Get_Options();
				$fields = array(
					'box',
					'left',
					'right',
					'close',
					'delay',
					'off',
					'coockie'
				);
				foreach ( $fields as $key ) {
					$field = $_POST['slidein'][$key];
					if (!is_null($field) && $field != "") {
						$o[$post_id][$key] = $field;
					} else {
						unset($o[$post_id][$key]);
					}
				}
				update_option('slide_in_popup', $o);
			}
			
        }
        
        /**
         * display the meta boxes
         *
         * @param unknown_type $post
         */
        function TwobuySlideInPopupAdmin_Edit_Entry($post) {
        	
        	// it's admin side, so options are better place
        	$sidebars_widgets = get_option('sidebars_widgets', array());
        	$sidebarT = $sidebars_widgets["slide_in_templates"];
        	$widgetsT = get_option('widget_slideins');

        	$wids = array();
        	$wnames = array();
        	foreach ((array) $sidebarT as $sw) {
        		if (preg_match('/^slideins-(\d+)$/',$sw,$matches) > 0) {
        			$wids[] = $matches[1];
        			$wnames[] = $widgetsT[$matches[1]]['title'];
        		}
        	}
        	
        	$post_id = $post->ID;
        	$o = TwobuySlideInPopup::TwobuySlideInPopup_Get_Options();
			//var_dump($o);
			        	
        	echo '<table style="width: 100%; border-collapse: collapse; padding: 2px 0px; spacing: 2px 0px;">';
        	echo '<tr valign="top"'
        		. '<td colspan="3" style="padding:15px 0;">'
				. __('Select the SlideIn box that you wish to use on this page/post, and it\'s options', 'slide-in-popup')
        		. '</td>'
        		. '</tr>';
        	echo '<tr valign="top">' . "\n"
        		. '<td style="padding:10px 0;">'
				. 'Disable slidein for this page/post: '
				. '</td>' . "\n"
				. '<td style="padding:10px 0;">'
				. '<input type="checkbox" name="slidein[off]" value="1" '.($o[$post_id]['off'] == "1"?'checked':'').'/>'
				. '</td>'
				. '<td style="text-align:right;padding:15px 0;">'
				. 'If this is checked then SlideIn will be completelly disabled on this page/post'
				. '</td>' . "\n"
        		. '</tr>'."\n";
        	echo '<tr valign="top">' . "\n"
        		. '<td style="padding:10px 0;">'
				. 'SlideIn Box: '
				. '</td>' . "\n"
				. '<td colspan="2" style="padding:10px 0;">'
				. '<select name="slidein[box]">'
				. '<option value="">Select the SlideIn Box</option>';
			foreach ((array) $wids as $k => $v) {
				echo '<option '.($v==$o[$post_id]['box']?'selected':'').' value="'.$v.'">'.$wnames[$k].'</option>';
			}
			echo '</select>'
				. '</td>' . "\n"
        		. '</tr>'."\n";
        	echo '<tr valign="top">' . "\n"
        		. '<td style="padding:10px 0;">'
				. 'Left side distance: '
				. '</td>' . "\n"
				. '<td colspan="2" style="padding:10px 0;">'
				. '<input type="text" name="slidein[left]" size="6" value="'.(isset($o[$post_id]['left'])?$o[$post_id]['left']:'').'"/>  Example "200px" or "40%"'
				. '</td>'
        		. '</tr>'."\n";
        	echo '<tr valign="top">' . "\n"
        		. '<td style="padding:10px 0;">'
				. 'Right side distance: '
				. '</td>' . "\n"
				. '<td colspan="2" style="padding:10px 0;">'
				. '<input type="text" name="slidein[right]" size="6" value="'.(isset($o[$post_id]['right'])?$o[$post_id]['right']:'').'"/>  Example "200px" or "40%"'
				. '</td>'
        		. '</tr>'."\n";
        	
        	echo '<tr valign="top">' . "\n"
        		. '<td style="padding:10px 0;">'
				. 'Delay: '
				. '</td>' . "\n"
				. '<td style="padding:10px 0;">'
				. '<input type="text" name="slidein[delay]" size="3" value="'.(isset($o[$post_id]['delay'])?$o[$post_id]['delay']:'').'"/> seconds'
				. '</td>'
				. '<td style="text-align:right;padding:10px 0;">'
				. 'Use 0 to reset the global delay.'
				. '</td>' . "\n"
        		. '</tr>'."\n";
        	if (isset($o[$post_id]['close'])) {
        		$clo = $o[$post_id]['close'];
        	} elseif (isset($o[0]['close'])) {
        		$clo = $o[0]['close'];
        	} else {
        		$clo = 'none';
        	}
        	echo '<tr valign="top">' . "\n"
        		. '<td style="padding:10px 0;">'
				. 'Close policy: '
				. '</td>' . "\n"
				. '<td colspan="2" style="padding:10px 0;">'
				. '<input type="radio" name="slidein[close]" value="close" '.($clo == 'close'?'checked':'').'/> Close button '
				. '<input type="radio" name="slidein[close]" value="toggle" '.($clo == 'toggle'?'checked':'').'/> Toggle button '
				. '<input type="radio" name="slidein[close]" value="none" '.($clo == 'none'?'checked':'').'/> None'
				. '</td>'
        		. '</tr>'."\n";
        	if (isset($o[$post_id]['coockie'])) {
        		$coc = $o[$post_id]['coockie'];
        	} elseif (isset($o[0]['coockie'])) {
        		$coc = $o[0]['coockie'];
        	} else {
        		$coc = 'none';
        	}
        	echo '<tr valign="top">' . "\n"
        		. '<td style="padding:10px 0;">'
				. 'Coockie conditions: '
				. '</td>' . "\n"
				. '<td colspan="2" style="padding:10px 0;">'
				. '<input type="radio" name="slidein[coockie]" value="none" '.($coc == 'none'?'checked':'').'/> Show every time page is visited '
				. '<input type="radio" name="slidein[coockie]" value="session" '.($coc == 'session'?'checked':'').'/> Only show once per page per visit '
				. '<input type="radio" name="slidein[coockie]" value="coockie" '.($coc == 'coockie'?'checked':'').'/> Only show once per page on first visit'
				. '</td>'
        		. '</tr>'."\n";
        	echo '</table>' . "\n";
        	
        }
       
    }
}


if (class_exists("TwobuySlideInPopupAdmin")) {
	$twobuy_slp_admin_object = new TwobuySlideInPopupAdmin();
}
//Actions and Filters	
if (isset($twobuy_slp_admin_object)) {
	add_action('save_post', array(&$twobuy_slp_admin_object, 'TwobuySlideInPopupAdmin_Save_Entry'));
}
?>