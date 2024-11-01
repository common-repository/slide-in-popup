<?php
/*
Plugin Name: Slide In Popup
Plugin URI: http://www.vuleticd.com/products/wordpress-plugins/slide-in-popup/
Description: Define the bottom edge slide-in box with any HTML/PHP globally or for each WP page/post.
Version: 0.1
Author: Dragan Vuletic
Author URI: http://www.vuleticd.com
Usage: View readme.txt

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
load_plugin_textdomain('slide-in-popup', false, dirname(plugin_basename(__FILE__)) . '/lang');
define('TWOBUY_SLP_PLUGIN_DIR',dirname(__FILE__));         //Abs path to the plugin directory
define('TWOBUY_SLP_PLUGIN_URL','/wp-content/plugins/slide-in-popup');         //Rel URL to the plugin directory

if (!class_exists('TwobuySlideInPopup')) {
	
	class TwobuySlideInPopup {
		public $abs_site_root;
		public $site_http_root;
		public $options;  // holds calculated options for each visited page on site. 
		public $run;  // the trigger calculated from session or coockie settings.
		
        function TwobuySlideInPopup() {
         	$this->site_http_root = get_option('siteurl');
         	//Root dir of the current site 
         	$this->abs_site_root= ABSPATH;         	
        }
        
        /**
         * plugin options controller
        */
        function TwobuySlideInPopup_Get_Options() {
			static $o;
		
			if ( isset($o) && !is_admin() ) {
				return $o;
			}
			$o = get_option('slide_in_popup');
		
			if ( $o === false ) {
				$o = TwobuySlideInPopup::TwobuySlideInPopup_Init_Options();
			}
			return $o;
		}
	
		function TwobuySlideInPopup_Init_Options() {
			// only define the blank global options as defaults
			$o = array(
				0 => array(), 
			);
			update_option('slide_in_popup', $o);		
			return $o;
		}
		
        /**
         * Declares the mootools v1.2 as the default loaded scripts in WP if MootoolsMagic plugin is not loaded, so from now on, any plugin can use it with wp_enqueue_script( $handle, $src = false, $deps = array(), $ver = false ) 
         *
         */
        function TwobuySlideInPopup_Add_Mootools(&$scripts) {
        	if (!isset($twobuy_mm_object)) {
        		if (!$guessurl = site_url())
				$guessurl = wp_guess_url();

				$scripts->base_url = $guessurl;
				$scripts->default_version = get_bloginfo( 'version' );
        		$scripts->add( 'mootools-core', TWOBUY_SLP_PLUGIN_URL.'/mootools/mootools-core.js', false, '1.2' );
				$scripts->add( 'mootools-more', TWOBUY_SLP_PLUGIN_URL.'/mootools/mootools-more.js', array('mootools-core'), '1.2' );
        	}
        }   
		
        /**
         * Definings the sidebar pannel for slidein widgets
         *
         */
        function TwobuySlideInPopup_Add_WPanel() {
        	
        	register_sidebar(
			array(
				'id' => 'slide_in_templates',
				'name' => 'SlideIn Boxes',
				'description' => 'Container for SlideIn Box widgets. Any other widget in here will not be used at all.',
				'before_widget' => '',
				'after_widget' => '' . "\n",
				'before_title' => '',
				'after_title' => '' . "\n",
				)
			);
        }

        /**
         * Decides if we should display the SlideIn on visited page/post, based on Coockie or Session content.
         * Used on init and wp-footer to prevent code injecting if it is not needed. 
         *
         * @param unknown_type $ID - ID of page/post, or 0 for archives pages
         */
        function TwobuySlideInPopup_Coockie_Conditions($ID) {
        	@session_start();
			$coockie_type = (isset($this->options['coockie'])?$this->options['coockie']:'none');
			
			$check_session = (isset($_SESSION['slidein'][$ID])?1:0);
			$check_cookie = (isset($_COOKIE['slidein-'.$ID])?1:0);
			
			switch ($coockie_type) {
				case 'session':
					wp_enqueue_script("slidein-coockie",TWOBUY_SLP_PLUGIN_URL.'/mootools/coockie.php?put=0&id='.$ID,false);
					$_SESSION['slidein'][$ID] = 1;
					$this->run = ($check_session == 0 ? true: false );
					break;
				case 'coockie':
					unset($_SESSION['slidein'][$ID]);
					wp_enqueue_script("slidein-coockie",TWOBUY_SLP_PLUGIN_URL.'/mootools/coockie.php?put=1&id='.$ID,false);
					$this->run = ($check_cookie == 0 ? true: false);
					break;
				case 'none':
					unset($_SESSION['slidein'][$ID]);
					wp_enqueue_script("slidein-coockie",TWOBUY_SLP_PLUGIN_URL.'/mootools/coockie.php?put=0&id='.$ID,false);
					$this->run = true;
					break;
			}
        }
        
        /**
         * Enter description here...
         *
         */
        function TwobuySlideInPopup_Add_Init() {
        	global $post;
		
			if (is_singular()) {
		  		$ID = $post->ID;
			} else {
		  		$ID = 0;
			}
        	
			$o = TwobuySlideInPopup::TwobuySlideInPopup_Get_Options();
			
			if ($ID != 0) {
				if (!isset($o[$ID]) || $o[$ID] == array()) {
					$this->options = $o[0];
				} else {
					// merge the _single options with globals on post/pages
					$options = array_merge($o[0],$o[$ID]);
					$this->options = $options;
				}
			} else {
				$this->options = $o[0];
			}
		 
        	if (!is_admin()) {
        		$this->TwobuySlideInPopup_Coockie_Conditions($ID);
        		
        		if (isset($this->options['box']) && !isset($this->options['off']) && $this->run) {
        			$delay = (isset($this->options['delay'])?$this->options['delay']:0);
        			wp_enqueue_style( 'slidein-slider',TWOBUY_SLP_PLUGIN_URL.'/css/slidein.css',false);
        			wp_enqueue_script("slidein-slider",TWOBUY_SLP_PLUGIN_URL.'/mootools/slidein-slider.php?delay='.$delay,array('mootools-more'));
        		}
        	}
        }
       
       /**
        * Add widget content before </body> tag.
        *
        */
       function TwobuySlideInPopup_Add_Slidein_Box() {
       		global $wp_registered_widgets;
       		
       		$args = array(
					'before_widget' => '',
					'after_widget' => '',
					'before_title' => '',
					'after_title' => ''
				);
       		
        	/* Horizontalni sidebarovi
        	echo '<div id="slidein_wrap" style="position:fixed;right:0;top:100px;z-index:99;overflow:hidden;">'
        			.'<div id="slidein_slider" style="overflow:hidden;position:relative;">'
        			.$widgetsT[$o[0]['box']]['text']
        			.'</div>'
        		.'</div>';
        	*/	
        	if (isset($this->options['box']) && !isset($this->options['off']) && $this->run) {
        		
        		$widget = $wp_registered_widgets['slideins-'.$this->options['box']];
        		$params = array($args, (array) $widget['params'][0]);
        		ob_start();
        		if (is_callable($widget['callback'])) {
					call_user_func_array($widget['callback'], $params);
        		}
				$label = ob_get_clean();

        		$left = (isset($this->options['left'])?'left:'.$this->options['left'].';':'');
        		$right = (isset($this->options['right'])?'right:'.$this->options['right'].';':'');
        		$close = ( !isset($this->options['close']) ? 'none' : $this->options['close'] );
        		
        		echo '<div id="slidein_wrap" style="'.$left.$right.'">'
        			. ($close == 'toggle'?'<a href="javascript:void(0);" class="slidein_toggle"></a><div class="slidein_clear"></div>':'')
        			.'<div id="slidein_slider">'
        			. ($close == 'close'?'<a href="javascript:void(0);" class="slidein_close"></a><div class="slidein_clear"></div>':'')
        			.$label
        			.'</div>'
        		.'</div>';
        	}
       }
        
        function TwobuySlideInPopup_Meta_Boxes() {
        	if ( current_user_can('unfiltered_html') ) {
        		// settings page
        		add_options_page("Global Slide In", "Global Slide In", 'manage_options', "global-slidein", array('TwobuySlideInPopupAdmin', 'TwobuySlideInPopupAdmin_Make_Admin_Page'));
        		// meta boxes fro page/post
				if ( current_user_can('edit_posts') )
					add_meta_box('slide_in_popup', __('Slide In', 'slide-in-popup'), array('TwobuySlideInPopupAdmin', 'TwobuySlideInPopupAdmin_Edit_Entry'), 'post');
				if ( current_user_can('edit_pages') )
					add_meta_box('slide_in_popup', __('Slide In', 'slide-in-popup'), array('TwobuySlideInPopupAdmin', 'TwobuySlideInPopupAdmin_Edit_Entry'), 'page');
			}
        }
    }
}


if (class_exists("TwobuySlideInPopup")) {
	$twobuy_slp_object = new TwobuySlideInPopup();
}
//Actions and Filters	
if (isset($twobuy_slp_object)) {
	// Add a SlideIn Box widget 
	include_once(TWOBUY_SLP_PLUGIN_DIR.'/slide-in-popup-template-widget.php');
	// include admin related scripts on edit page post and settings
	function slide_in_popup_admin() {
		include TWOBUY_SLP_PLUGIN_DIR . '/slide-in-popup-admin.php';
	}
	foreach ( array('page-new.php', 'page.php', 'post-new.php', 'post.php', 'settings_page_global-slidein') as $hook ) {
		add_action("load-$hook", 'slide_in_popup_admin');
	}
	
	add_action('admin_menu', array(&$twobuy_slp_object, 'TwobuySlideInPopup_Meta_Boxes'), 30);
	// Let WP know about mootools, so we can use dependencies in plugin. 
	add_action('wp_default_scripts', array(&$twobuy_slp_object, 'TwobuySlideInPopup_Add_Mootools'), 0);
    add_action('init', array(&$twobuy_slp_object, 'TwobuySlideInPopup_Add_WPanel'), 0);
    add_action('wp_head', array(&$twobuy_slp_object, 'TwobuySlideInPopup_Add_Init'), 0);
    add_action('wp_footer', array(&$twobuy_slp_object, 'TwobuySlideInPopup_Add_Slidein_Box'));
}
?>