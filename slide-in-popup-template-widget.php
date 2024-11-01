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

class SlideIn_Template extends WP_Widget {

	function SlideIn_Template() {
		$widget_ops = array(
				'classname' => 'widget_slide_in_template', 
				'description' => __('HTML or PHP code used as content for SlideIn boxes. Enclose the PHP code inside "<?php  ?>". If used outside of SlideIn Boxes sidebar, it will behaive just like "Executable PHP widget" widget, without titles.')
			);
		$control_ops = array('width' => 400, 'height' => 350);
		$this->WP_Widget('slideins', __('SlideIn Box'), $widget_ops, $control_ops);
	}

	function widgets_init() {
		register_widget('SlideIn_Template');
	}
	
	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? 'No Title' : $instance['title']);
		$text = apply_filters( 'widget_slide_in_template', $instance['text'] );
		echo $before_widget;

		ob_start();
		eval('?>'.$text);
		$text = ob_get_contents();
		ob_end_clean();
		echo $text;
		
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  stripslashes($new_instance['text']);
		else
			$instance['text'] = stripslashes(wp_filter_post_kses( $new_instance['text'] ));
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, SlideIn_Template::defaults() );
		$title = strip_tags($instance['title']);
		$text = format_to_edit($instance['text']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
<?php
	}
	
	function defaults() {
		return array(
			'title' => '',
			'text'	=> '',
		);
	}
}
add_action('widgets_init', array('SlideIn_Template', 'widgets_init'));
?>