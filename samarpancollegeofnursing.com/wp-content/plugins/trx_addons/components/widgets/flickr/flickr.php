<?php
/**
 * Widget: Flickr
 *
 * @package WordPress
 * @subpackage trx_addons Addons
 * @since v1.1
 */

// Load widget
if (!function_exists('trx_addons_widget_flickr_load')) {
	add_action( 'widgets_init', 'trx_addons_widget_flickr_load' );
	function trx_addons_widget_flickr_load() {
		register_widget('trx_addons_widget_flickr');
	}
}

// Widget Class
class trx_addons_widget_flickr extends TRX_Addons_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_flickr', 'description' => esc_html__('Last flickr photos.', 'trx_addons') );
		parent::__construct( 'trx_addons_widget_flickr', esc_html__('ThemeREX Flickr photos', 'trx_addons'), $widget_ops );
	}

	// Show widget
	function widget( $args, $instance ) {

		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$flickr_username = isset($instance['flickr_username']) ? $instance['flickr_username'] : '';
		$flickr_count = isset($instance['flickr_count']) ? $instance['flickr_count'] : '';

		trx_addons_get_template_part(TRX_ADDONS_PLUGIN_WIDGETS . 'flickr/tpl.default.php',
									'trx_addons_args_widget_flickr', 
									apply_filters('trx_addons_filter_widget_args',
												array_merge($args, compact('title', 'flickr_username', 'flickr_count')),
												$instance, 'trx_addons_widget_flickr')
									);
	}

	// Update the widget settings.
	function update( $new_instance, $instance ) {
		$instance = array_merge($instance, $new_instance);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['flickr_username'] = strip_tags( $new_instance['flickr_username'] );
		$instance['flickr_count'] = (int) $new_instance['flickr_count'];
		return apply_filters('trx_addons_filter_widget_args_update', $instance, $new_instance, 'trx_addons_widget_flickr');
	}

	// Displays the widget settings controls on the widget panel.
	function form( $instance ) {
		
		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, apply_filters('trx_addons_filter_widget_args_default', array(
			'title' => '', 
			'flickr_username' => '', 
			'flickr_count' => '' 
			), 'trx_addons_widget_flickr')
		);
		
		do_action('trx_addons_action_before_widget_fields', $instance, 'trx_addons_widget_flickr');
		
		$this->show_field(array('name' => 'title',
								'title' => __('Title:', 'trx_addons'),
								'value' => $instance['title'],
								'type' => 'text'));
		
		do_action('trx_addons_action_after_widget_title', $instance, 'trx_addons_widget_flickr');
		
		$this->show_field(array('name' => 'flickr_username',
								'title' => __('Flickr ID:', 'trx_addons'),
								'value' => $instance['flickr_username'],
								'type' => 'text'));
		
		$this->show_field(array('name' => 'flickr_count',
								'title' => __('Number of photos:', 'trx_addons'),
								'value' => max(1, min(30, (int) $instance['flickr_count'])),
								'type' => 'text'));
		
		do_action('trx_addons_action_after_widget_fields', $instance, 'trx_addons_widget_flickr');
	}
}

	
// Merge widget specific styles into single stylesheet
if ( !function_exists( 'trx_addons_widget_flickr_merge_styles' ) ) {
	add_filter("trx_addons_filter_merge_styles", 'trx_addons_widget_flickr_merge_styles');
	function trx_addons_widget_flickr_merge_styles($list) {
		$list[] = TRX_ADDONS_PLUGIN_WIDGETS . 'flickr/_flickr.scss';
		return $list;
	}
}



// trx_widget_flickr
//-------------------------------------------------------------
/*
[trx_widget_flickr id="unique_id" title="Widget title" flickr_count="6" flickr_username="Flickr@23"]
*/
if ( !function_exists( 'trx_addons_sc_widget_flickr' ) ) {
	function trx_addons_sc_widget_flickr($atts, $content=null){	
		$atts = trx_addons_sc_prepare_atts('trx_widget_flickr', $atts, array(
			// Individual params
			"title"			=> "",
			'flickr_count'	=> 6,
			'flickr_username' => '',
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);
		extract($atts);
		$type = 'trx_addons_widget_flickr';
		$output = '';
		if ( (int) $atts['flickr_count'] > 0 && !empty($atts['flickr_username']) ) {
			global $wp_widget_factory;
			if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
				$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
								. ' class="widget_area sc_widget_flickr' 
									. (trx_addons_exists_visual_composer() ? ' vc_widget_flickr wpb_content_element' : '') 
									. (!empty($class) ? ' ' . esc_attr($class) : '') 
								. '"'
							. ($css ? ' style="'.esc_attr($css).'"' : '')
						. '>';
				ob_start();
				the_widget( $type, $atts, trx_addons_prepare_widgets_args($id ? $id.'_widget' : 'widget_flickr', 'widget_flickr') );
				$output .= ob_get_contents();
				ob_end_clean();
				$output .= '</div>';
			}
		}
		return apply_filters('trx_addons_sc_output', $output, 'trx_widget_flickr', $atts, $content);
	}
}


// Add [trx_widget_flickr] in the VC shortcodes list
if (!function_exists('trx_addons_widget_flickr_reg_shortcodes_vc')) {
	function trx_addons_widget_flickr_reg_shortcodes_vc() {

		add_shortcode("trx_widget_flickr", "trx_addons_sc_widget_flickr");
		
		if (!trx_addons_exists_visual_composer()) return;
		
		vc_lean_map("trx_widget_flickr", 'trx_addons_widget_flickr_reg_shortcodes_vc_params');
		class WPBakeryShortCode_Trx_Widget_Flickr extends WPBakeryShortCode {}
	}
	add_action('init', 'trx_addons_widget_flickr_reg_shortcodes_vc', 20);
}

// Return params
if (!function_exists('trx_addons_widget_flickr_reg_shortcodes_vc_params')) {
	function trx_addons_widget_flickr_reg_shortcodes_vc_params() {
		return apply_filters('trx_addons_sc_map', array(
				"base" => "trx_widget_flickr",
				"name" => esc_html__("Flickr photos", 'trx_addons'),
				"description" => wp_kses_data( __("Display the latest photos from Flickr account", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_widget_flickr',
				"class" => "trx_widget_flickr",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array_merge(
					array(
						array(
							"param_name" => "title",
							"heading" => esc_html__("Widget title", 'trx_addons'),
							"description" => wp_kses_data( __("Title of the widget", 'trx_addons') ),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "flickr_username",
							"heading" => esc_html__("Flickr username", 'trx_addons'),
							"description" => wp_kses_data( __("Your Flickr username", 'trx_addons') ),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "flickr_count",
							"heading" => esc_html__("Number of photos", 'trx_addons'),
							"description" => wp_kses_data( __("How many photos to be displayed?", 'trx_addons') ),
							"class" => "",
							"value" => "6",
							"type" => "textfield"
						)
					),
					trx_addons_vc_add_id_param()
				)
			), 'trx_widget_flickr');
	}
}
?>