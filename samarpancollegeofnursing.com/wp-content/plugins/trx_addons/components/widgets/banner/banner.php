<?php
/**
 * Widget: Banner
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0
 */

// Load widget
if (!function_exists('trx_addons_widget_banner_load')) {
	add_action( 'widgets_init', 'trx_addons_widget_banner_load' );
	function trx_addons_widget_banner_load() {
		register_widget( 'trx_addons_widget_banner' );
	}
}

// Widget Class
class trx_addons_widget_banner extends TRX_Addons_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_banner', 'description' => esc_html__('Banner with image and/or any html and js code', 'trx_addons') );
		parent::__construct( 'trx_addons_widget_banner', esc_html__('ThemeREX Banner', 'trx_addons'), $widget_ops );
	}

	// Show widget
	function widget( $args, $instance ) {

		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$fullwidth = isset($instance['fullwidth']) ? $instance['fullwidth'] : '';
		$banner_link = isset($instance['banner_link']) ? $instance['banner_link'] : '';
		$banner_code = isset($instance['banner_code']) ? $instance['banner_code'] : '';
		$banner_image = isset($instance['banner_image']) ? $instance['banner_image'] : '';
		if (empty($banner_image)) {
			if (empty($banner_link) && empty($banner_code) && is_singular() && !trx_addons_sc_layouts_showed('featured')) {
				$banner_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), !empty($instance['from_shortcode']) ? 'full' : trx_addons_get_thumb_size('masonry') );
				$banner_image = $banner_image[0];
			}
		} else
			$banner_image = trx_addons_get_attachment_url($banner_image, !empty($instance['from_shortcode']) ? 'full' : trx_addons_get_thumb_size('masonry'));

		trx_addons_get_template_part(TRX_ADDONS_PLUGIN_WIDGETS . 'banner/tpl.default.php',
									'trx_addons_args_widget_banner',
									apply_filters('trx_addons_filter_widget_args',
												array_merge($args, compact('title', 'fullwidth', 'banner_image', 'banner_link', 'banner_code')),
												$instance, 'trx_addons_widget_banner')
									);
	}

	// Update the widget settings.
	function update( $new_instance, $instance ) {
		$instance = array_merge($instance, $new_instance);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['fullwidth'] = strip_tags( $new_instance['fullwidth'] );
		$instance['banner_image'] = strip_tags( $new_instance['banner_image'] );
		$instance['banner_link'] = strip_tags( $new_instance['banner_link'] );
		$instance['banner_code'] = $new_instance['banner_code'];
		return apply_filters('trx_addons_filter_widget_args_update', $instance, $new_instance, 'trx_addons_widget_banner');
	}

	// Displays the widget settings controls on the widget panel.
	function form( $instance ) {

		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, apply_filters('trx_addons_filter_widget_args_default', array(
			'title' => '',
			'fullwidth' => '1',
			'banner_image' => '',
			'banner_link' => '',
			'banner_code' => ''
			), 'trx_addons_widget_banner')
		);
		
		do_action('trx_addons_action_before_widget_fields', $instance, 'trx_addons_widget_banner');
		
		$this->show_field(array('name' => 'title',
								'title' => __('Title:', 'trx_addons'),
								'value' => $instance['title'],
								'type' => 'text'));
		
		do_action('trx_addons_action_after_widget_title', $instance, 'trx_addons_widget_banner');
		
		$this->show_field(array('name' => 'fullwidth',
								'title' => __('Widget size:', 'trx_addons'),
								'value' => $instance['fullwidth'],
								'options' => array(
													'1' => __('Fullwidth', 'trx_addons'),
													'0' => __('Boxed', 'trx_addons')
													),
								'type' => 'switch'));
		
		$this->show_field(array('name' => 'banner_image',
								'title' => __('Image source URL:', 'trx_addons'),
								'value' => $instance['banner_image'],
								'type' => 'image'));
		
		$this->show_field(array('name' => 'banner_link',
								'title' => __('Image link URL:', 'trx_addons'),
								'value' => $instance['banner_link'],
								'type' => 'text'));

		$this->show_field(array('name' => 'banner_code',
								'title' => __('Paste HTML Code:', 'trx_addons'),
								'value' => $instance['banner_code'],
								'type' => 'textarea'));
		
		do_action('trx_addons_action_after_widget_fields', $instance, 'trx_addons_widget_banner');
	}
}

	
// Merge widget specific styles into single stylesheet
if ( !function_exists( 'trx_addons_widget_banner_merge_styles' ) ) {
	add_filter("trx_addons_filter_merge_styles", 'trx_addons_widget_banner_merge_styles');
	function trx_addons_widget_banner_merge_styles($list) {
		$list[] = TRX_ADDONS_PLUGIN_WIDGETS . 'banner/_banner.scss';
		return $list;
	}
}



// trx_widget_banner
//-------------------------------------------------------------
/*
[trx_widget_banner id="unique_id" title="Widget title" fullwidth="0|1" image="image_url" link="Image_link_url" code="html & js code"]
*/
if ( !function_exists( 'trx_addons_sc_widget_banner' ) ) {
	function trx_addons_sc_widget_banner($atts, $content=null){	
		$atts = trx_addons_sc_prepare_atts('trx_widget_banner', $atts, array(
			// Individual params
			"title" => "",
			"image" => "",
			"link" => "",
			"code" => "",
			"fullwidth" => "off",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);
		extract($atts);
		$type = 'trx_addons_widget_banner';
		$output = '';
		global $wp_widget_factory;
		if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
			$atts['from_shortcode'] = true;
			$atts['banner_image'] = $image;
			$atts['banner_link'] = $link;
			$atts['banner_code'] = !empty($code) ? trim( vc_value_from_safe( $code ) ) : '';
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
							. ' class="widget_area sc_widget_banner' 
								. (trx_addons_exists_visual_composer() ? ' vc_widget_banner wpb_content_element' : '') 
								. (!empty($class) ? ' ' . esc_attr($class) : '') 
								. '"'
							. ($css ? ' style="'.esc_attr($css).'"' : '')
						. '>';
			ob_start();
			the_widget( $type, $atts, trx_addons_prepare_widgets_args($id ? $id.'_widget' : 'widget_banner', 'widget_banner') );
			$output .= ob_get_contents();
			ob_end_clean();
			$output .= '</div>';
		}
		return apply_filters('trx_addons_sc_output', $output, 'trx_widget_banner', $atts, $content);
	}
}


// Add [trx_widget_banner] in the VC shortcodes list
if (!function_exists('trx_addons_sc_widget_banner_add_in_vc')) {
	function trx_addons_sc_widget_banner_add_in_vc() {
		
		add_shortcode("trx_widget_banner", "trx_addons_sc_widget_banner");
		
		if (!trx_addons_exists_visual_composer()) return;
		
		vc_lean_map("trx_widget_banner", 'trx_addons_sc_widget_banner_add_in_vc_params');
		class WPBakeryShortCode_Trx_Widget_Banner extends WPBakeryShortCode {}
	}
	add_action('init', 'trx_addons_sc_widget_banner_add_in_vc', 20);
}

// Return params
if (!function_exists('trx_addons_sc_widget_banner_add_in_vc_params')) {
	function trx_addons_sc_widget_banner_add_in_vc_params() {
		return apply_filters('trx_addons_sc_map', array(
				"base" => "trx_widget_banner",
				"name" => esc_html__("Banner", 'trx_addons'),
				"description" => wp_kses_data( __("Insert widget with banner or any HTML and/or Javascript code", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_widget_banner',
				"class" => "trx_widget_banner",
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
							"type" => "textfield"
						),
						array(
							"param_name" => "image",
							"heading" => esc_html__("Image", 'trx_addons'),
							"description" => wp_kses_data( __("Select or upload image or write URL from other site for the banner (leave empty if you paste banner code)", 'trx_addons') ),
							"type" => "attach_image"
						),
						array(
							"param_name" => "link",
							"heading" => esc_html__("Banner's link", 'trx_addons'),
							"description" => wp_kses_data( __("Link URL for the banner (leave empty if you paste banner code)", 'trx_addons') ),
							"type" => "textfield"
						),
						array(
							"param_name" => "code",
							"heading" => esc_html__("or paste HTML Code", 'trx_addons'),
							"description" => wp_kses_data( __("Widget's HTML and/or JS code", 'trx_addons') ),
							"type" => "textarea_safe"
						)
					),
					trx_addons_vc_add_id_param()
				)
			), 'trx_widget_banner' );
	}
}
?>