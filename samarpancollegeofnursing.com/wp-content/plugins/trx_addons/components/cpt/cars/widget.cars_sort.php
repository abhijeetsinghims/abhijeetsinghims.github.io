<?php
/**
 * Widget: Cars Sort
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.25
 */

// Load widget
if (!function_exists('trx_addons_widget_cars_sort_load')) {
	add_action( 'widgets_init', 'trx_addons_widget_cars_sort_load' );
	function trx_addons_widget_cars_sort_load() {
		register_widget('trx_addons_widget_cars_sort');
	}
}

// Widget Class
class trx_addons_widget_cars_sort extends TRX_Addons_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_cars_sort', 'description' => esc_html__('Sort cars list by date, price or title', 'trx_addons'));
		parent::__construct( 'trx_addons_widget_cars_sort', esc_html__('ThemeREX Cars Sort', 'trx_addons'), $widget_ops );
	}

	// Show widget
	function widget($args, $instance) {
		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '');

		$orderby = isset($instance['orderby']) ? $instance['orderby'] : 'date';
		$order = isset($instance['order']) ? $instance['order'] : 'desc';

		trx_addons_get_template_part(TRX_ADDONS_PLUGIN_CPT . 'cars/tpl.widget.cars_sort.php',
										'trx_addons_args_widget_cars_sort',
										apply_filters('trx_addons_filter_widget_args',
											array_merge($args, compact('title', 'orderby', 'order')),
											$instance, 'trx_addons_widget_cars_sort')
									);
	}

	// Update the widget settings.
	function update($new_instance, $instance) {
		$instance = array_merge($instance, $new_instance);
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['orderby'] = strip_tags($new_instance['orderby']);
		$instance['order'] = strip_tags($new_instance['order']);
		return apply_filters('trx_addons_filter_widget_args_update', $instance, $new_instance, 'trx_addons_widget_cars_sort');
	}

	// Displays the widget settings controls on the widget panel.
	function form($instance) {

		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, apply_filters('trx_addons_filter_widget_args_default', array(
			'title' => '',
			'orderby' => 'date',
			'order' => 'desc'
			), 'trx_addons_widget_cars_sort')
		);
		
		do_action('trx_addons_action_before_widget_fields', $instance, 'trx_addons_widget_cars_sort');
		
		$this->show_field(array('name' => 'title',
								'title' => __('Widget title:', 'trx_addons'),
								'value' => $instance['title'],
								'type' => 'text'));
		
		do_action('trx_addons_action_after_widget_title', $instance, 'trx_addons_widget_cars_sort');

		$this->show_field(array('name' => 'orderby',
								'title' => __('Order search results by:', 'trx_addons'),
								'value' => $instance['orderby'],
								'options' => array(
													'date' => __('Date', 'trx_addons'),
													'price' => __('Price', 'trx_addons'),
													'title' => __('Title', 'trx_addons')
													),
								'type' => 'select'));

		$this->show_field(array('name' => 'order',
								'title' => __('Order:', 'trx_addons'),
								'value' => $instance['order'],
								'options' => array(
													'asc' => __('Ascending', 'trx_addons'),
													'desc' => __('Descending', 'trx_addons')
													),
								'type' => 'switch'));
		
		do_action('trx_addons_action_after_widget_fields', $instance, 'trx_addons_widget_cars_sort');
	}
}



// trx_widget_cars_sort
//-------------------------------------------------------------
/*
[trx_widget_cars_sort id="unique_id" title="Widget title" orderby="price" order="desc"]
*/
if ( !function_exists( 'trx_addons_sc_widget_cars_sort' ) ) {
	function trx_addons_sc_widget_cars_sort($atts, $content=null){	
		$atts = trx_addons_sc_prepare_atts('trx_widget_cars_sort', $atts, array(
			// Individual params
			"title" => "",
			"orderby" => "date",
			"order" => "desc",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);
		extract($atts);
		$type = 'trx_addons_widget_cars_sort';
		$output = '';
		global $wp_widget_factory;
		if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
							. ' class="widget_area sc_widget_cars_sort' 
								. (trx_addons_exists_visual_composer() ? ' vc_widget_cars_sort wpb_content_element' : '') 
								. (!empty($class) ? ' ' . esc_attr($class) : '') 
								. '"'
							. ($css ? ' style="'.esc_attr($css).'"' : '')
						. '>';
			ob_start();
			the_widget( $type, $atts, trx_addons_prepare_widgets_args($id ? $id.'_widget' : 'widget_cars_sort', 'widget_cars_sort') );
			$output .= ob_get_contents();
			ob_end_clean();
			$output .= '</div>';
		}
		return apply_filters('trx_addons_sc_output', $output, 'trx_widget_cars_sort', $atts, $content);
	}
}


// Add [trx_widget_cars_sort] in the VC shortcodes list
if (!function_exists('trx_addons_sc_widget_cars_sort_add_in_vc')) {
	function trx_addons_sc_widget_cars_sort_add_in_vc() {
		
		add_shortcode("trx_widget_cars_sort", "trx_addons_sc_widget_cars_sort");
		
		if (!trx_addons_exists_visual_composer()) return;
		
		vc_lean_map("trx_widget_cars_sort", 'trx_addons_sc_widget_cars_sort_add_in_vc_params');
		class WPBakeryShortCode_Trx_Widget_Cars_Sort extends WPBakeryShortCode {}
	}
	add_action('init', 'trx_addons_sc_widget_cars_sort_add_in_vc', 20);
}

// Return params
if (!function_exists('trx_addons_sc_widget_cars_sort_add_in_vc_params')) {
	function trx_addons_sc_widget_cars_sort_add_in_vc_params() {
		return apply_filters('trx_addons_sc_map', array(
				"base" => "trx_widget_cars_sort",
				"name" => esc_html__("Cars Sort", 'trx_addons'),
				"description" => wp_kses_data( __("Insert widget to sort cars by price, date or title", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_widget_cars_sort',
				"class" => "trx_widget_cars_sort",
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
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", 'trx_addons'),
							"description" => wp_kses_data( __("Select sorting type of cars", 'trx_addons') ),
							"std" => "date",
							'save_always' => true,
							"value" => array(
								esc_html__('Date', 'trx_addons') => 'date',
								esc_html__('Price', 'trx_addons') => 'price',
								esc_html__('Title', 'trx_addons') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", 'trx_addons'),
							"description" => wp_kses_data( __("Select sorting order of cars", 'trx_addons') ),
							"std" => "desc",
							'save_always' => true,
							"value" => array(
								esc_html__('Ascending', 'trx_addons') => 'asc',
								esc_html__('Descending', 'trx_addons') => 'desc'
							),
							"type" => "dropdown"
						)
					),
					trx_addons_vc_add_id_param()
				)
			), 'trx_widget_cars_sort' );
	}
}
?>