<?php
/**
 * ThemeREX Shortcodes
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}

// Define list with shortcodes
if (!function_exists('trx_addons_sc_setup')) {
	add_action( 'after_setup_theme', 'trx_addons_sc_setup', 2 );
	function trx_addons_sc_setup() {
		static $loaded = false;
		if ($loaded) return;
		$loaded = true;
		global $TRX_ADDONS_STORAGE;
		$TRX_ADDONS_STORAGE['sc_list'] = apply_filters('trx_addons_sc_list', array(
			'action' => array(
							'title' => __('Actions', 'trx_addons'),
							'layouts_sc' => array(
								'default' => esc_html__('Default', 'trx_addons'),
								'simple' => esc_html__('Simple', 'trx_addons'),
								'event' => esc_html__('Event', 'trx_addons')
							)
						),
			'anchor' => array(
							'title' => __('Anchor', 'trx_addons'),
							'layouts_sc' => array(
								'default' => esc_html__('Default', 'trx_addons')
							)
						),
			'blogger' => array(
							'title' => __('Blogger', 'trx_addons'),
							'layouts_sc' => array(
/*
								'default' => esc_html__('Default', 'trx_addons'),
								'modern' => esc_html__('Modern', 'trx_addons'),
								'plain' => esc_html__('Plain', 'trx_addons')
*/

								'default' => trx_addons_get_file_url(TRX_ADDONS_PLUGIN_SHORTCODES . 'blogger/type-default.png'),
								'modern' => trx_addons_get_file_url(TRX_ADDONS_PLUGIN_SHORTCODES . 'blogger/type-modern.png'),
								'plain' => trx_addons_get_file_url(TRX_ADDONS_PLUGIN_SHORTCODES . 'blogger/type-plain.png')

							)
						),
			'button' => array(
							'title' => __('Button', 'trx_addons'),
							'layouts_sc' => array(
								'default' => esc_html__('Default', 'trx_addons'),
								'bordered' => esc_html__('Bordered', 'trx_addons'),
								'simple' => esc_html__('Simple', 'trx_addons')
							),
							// Always enabled!!!
							'std' => 1,
							'hidden' => false
						),
			'content' => array(
							'title' => __('Content', 'trx_addons'),
							'layouts_sc' => array(
								'default' => esc_html__('Default', 'trx_addons'),
							),
							// Always enabled!!!
							'std' => 1,
							'hidden' => true
						),
			'countdown' => array(
							'title' => __('Countdown', 'trx_addons'),
							'layouts_sc' => array(
								'default' => esc_html__('Default', 'trx_addons'),
								'circle' => esc_html__('Circle', 'trx_addons')
							)
						),
			'form' => array(
							'title' => __('Forms', 'trx_addons'),
							'layouts_sc' => array(
								'default' => esc_html__('Default', 'trx_addons'),
								'modern' => esc_html__('Modern', 'trx_addons'),
								'detailed' => esc_html__('Detailed', 'trx_addons')
							),
							// Always enabled!!!
							'std' => 1,
							'hidden' => false
						),
			'googlemap' => array(
							'title' => __('Google map', 'trx_addons'),
							'layouts_sc' => array(
								'default' => esc_html__('Default', 'trx_addons'),
								'detailed' => esc_html__('Detailed', 'trx_addons')
							),
							// Always enabled!!!
							'std' => 1,
							'hidden' => false
						),
			'icons' => array(
							'title' => __('Icons', 'trx_addons'),
							'layouts_sc' => array(
								'default' => esc_html__('Default', 'trx_addons'),
								'modern' => esc_html__('Modern', 'trx_addons')
							)
						),
			'popup' => array(
							'title' => __('Popup', 'trx_addons'),
							'layouts_sc' => array(
								'default' => esc_html__('Default', 'trx_addons'),
							)
						),
			'price' => array(
							'title' => __('Price block', 'trx_addons'),
							'layouts_sc' => array(
								'default' => esc_html__('Default', 'trx_addons'),
							)
						),
			'promo' => array(
							'title' => __('Promo', 'trx_addons'),
							'layouts_sc' => array(
								'default' => esc_html__('Default', 'trx_addons'),
								'modern' => esc_html__('Modern', 'trx_addons'),
								'blockquote' => esc_html__('Blockquote', 'trx_addons')
							)
						),
			'skills' => array(
							'title' => __('Skills', 'trx_addons'),
							'layouts_sc' => array(
								'pie' => esc_html__('Pie', 'trx_addons'),
								'counter' => esc_html__('Counter', 'trx_addons')
							)
						),
			'socials' => array(
							'title' => __('Socials', 'trx_addons'),
							'layouts_sc' => array(
								'default' => esc_html__('Only icons', 'trx_addons'),
								'names' => esc_html__('Only names', 'trx_addons'),
								'icons_names' => esc_html__('Icon + name', 'trx_addons')
							),
							// Always enabled!!!
							'std' => 1,
							'hidden' => false
						),
			'table' => array(
							'title' => __('Table', 'trx_addons'),
							'layouts_sc' => array(
								'default' => esc_html__('Default', 'trx_addons'),
							)
						),
			'title' => array(
							'title' => __('Title', 'trx_addons'),
							'layouts_sc' => array(
								'default' => esc_html__('Default', 'trx_addons'),
								'shadow' => esc_html__('Shadow', 'trx_addons'),
								'accent' => esc_html__('Accent', 'trx_addons')
							),
							// Always enabled!!!
							'std' => 1,
							'hidden' => false
						)
			)
		);
	}
}

// Include files with shortcodes
if (!function_exists('trx_addons_sc_load')) {
	add_action( 'after_setup_theme', 'trx_addons_sc_load', 6 );
	function trx_addons_sc_load() {
		static $loaded = false;
		if ($loaded) return;
		$loaded = true;
		global $TRX_ADDONS_STORAGE;
		if (is_array($TRX_ADDONS_STORAGE['sc_list']) && count($TRX_ADDONS_STORAGE['sc_list']) > 0) {
			foreach ($TRX_ADDONS_STORAGE['sc_list'] as $sc=>$params) {
				if (trx_addons_components_is_allowed('sc', $sc)
					&& ($fdir = trx_addons_get_file_dir(TRX_ADDONS_PLUGIN_SHORTCODES . "{$sc}/{$sc}.php")) != '') { 
					include_once $fdir;
				}
			}
		}
	}
}

// Add 'Shortcodes' block in the ThemeREX Addons Components
if (!function_exists('trx_addons_sc_components')) {
	add_filter( 'trx_addons_filter_components_blocks', 'trx_addons_sc_components');
	function trx_addons_sc_components($blocks=array()) {
		$blocks['sc'] = __('Shortcodes', 'trx_addons');
		return $blocks;
	}
}

	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_sc_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_sc_load_scripts_front');
	function trx_addons_sc_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			wp_enqueue_script( 'trx_addons-sc', trx_addons_get_file_url(TRX_ADDONS_PLUGIN_SHORTCODES . 'shortcodes.js'), array('jquery'), null, true );
		}
	}
}


// Merge shortcode's specific styles to the single stylesheet
if ( !function_exists( 'trx_addons_sc_merge_styles' ) ) {
	add_filter("trx_addons_filter_merge_styles", 'trx_addons_sc_merge_styles');
	function trx_addons_sc_merge_styles($list) {
		$list[] = TRX_ADDONS_PLUGIN_SHORTCODES . '_shortcodes.scss';
		return $list;
	}
}


// Merge shortcode's specific styles to the single stylesheet (responsive)
if ( !function_exists( 'trx_addons_sc_merge_styles_responsive' ) ) {
	add_filter("trx_addons_filter_merge_styles_responsive", 'trx_addons_sc_merge_styles_responsive');
	function trx_addons_sc_merge_styles_responsive($list) {
		$list[] = TRX_ADDONS_PLUGIN_SHORTCODES . '_shortcodes.responsive.scss';
		return $list;
	}
}

	
// Merge shortcode's specific scripts to the single file
if ( !function_exists( 'trx_addons_sc_merge_scripts' ) ) {
	add_action("trx_addons_filter_merge_scripts", 'trx_addons_sc_merge_scripts');
	function trx_addons_sc_merge_scripts($list) {
		$list[] = TRX_ADDONS_PLUGIN_SHORTCODES . 'shortcodes.js';
		return $list;
	}
}

// Prepare Id, custom CSS and other parameters in the shortcode's atts
if (!function_exists('trx_addons_sc_prepare_atts')) {
	function trx_addons_sc_prepare_atts($sc, $atts, $defa) {
		// Push shortcode name to the stack
		trx_addons_sc_stack_push($sc);
		// Merge atts with default values
		$atts = trx_addons_html_decode(shortcode_atts(apply_filters('trx_addons_sc_atts', $defa, $sc), $atts));
		// Unsafe item description
		if (!empty($atts['description']) && function_exists('vc_value_from_safe'))
			$atts['description'] = trim( vc_value_from_safe( $atts['description'] ) );
		// Generate id (if empty)
        if (empty($atts['id']))
        	$atts['id'] = str_replace('trx_', '', $sc) . '_' . str_replace('.', '', mt_rand());
		// Add custom CSS class
		if (!empty($atts['css'])
			&& (trx_addons_sc_stack_check('show_layout_vc') || strpos($atts['css'], '.vc_custom_') !== false)
			&& defined('VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG')
			&& function_exists('vc_shortcode_custom_css_class')
		) {
			$atts['class'] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
				(!empty($atts['class']) ? $atts['class'] . ' ' : '') . vc_shortcode_custom_css_class( $atts['css'], ' ' ),
				$sc,
				$atts);
			$atts['css'] = '';
		}
 		return apply_filters('trx_addons_filter_sc_prepare_atts', $atts, $sc);
	}
}

// After all handlers are finished - pop sc from the stack
if (!function_exists('trx_addons_sc_output_finish')) {
	add_filter('trx_addons_sc_output', 'trx_addons_sc_output_finish', 9999, 4);
	function trx_addons_sc_output_finish($output='', $sc='', $atts='', $content='') {
		trx_addons_sc_stack_pop($sc);
		return $output;
	}
}

// Push shortcode name to the stack
if (!function_exists('trx_addons_sc_stack_push')) {
	function trx_addons_sc_stack_push($sc) {
		global $TRX_ADDONS_STORAGE;
		array_push($TRX_ADDONS_STORAGE['sc_stack'], $sc);
	}
}

// Pop shortcode name from the stack
if (!function_exists('trx_addons_sc_stack_pop')) {
	function trx_addons_sc_stack_pop() {
		global $TRX_ADDONS_STORAGE;
		return array_pop($TRX_ADDONS_STORAGE['sc_stack']);
	}
}

// Check if shortcode name is in the stack
if (!function_exists('trx_addons_sc_stack_check')) {
	function trx_addons_sc_stack_check($sc) {
		global $TRX_ADDONS_STORAGE;
		return in_array($sc, $TRX_ADDONS_STORAGE['sc_stack']);
	}
}


// Shortcodes parts
//---------------------------------------

// Enqueue iconed fonts
if (!function_exists('trx_addons_load_icons')) {
	function trx_addons_load_icons($list='') {
		if (!empty($list) && function_exists('vc_icon_element_fonts_enqueue')) {
			$list = explode(',', $list);
			foreach ($list as $icon_type)
				vc_icon_element_fonts_enqueue($icon_type);
		}
	}
}

// Display title, subtitle and description for some shortcodes
if (!function_exists('trx_addons_sc_show_titles')) {
	function trx_addons_sc_show_titles($sc, $args, $size='') {
		trx_addons_get_template_part('templates/tpl.sc_titles.php',
										'trx_addons_args_sc_show_titles',
										compact('sc', 'args', 'size')
									);
	}
}

// Display link button or image for some shortcodes
if (!function_exists('trx_addons_sc_show_links')) {
	function trx_addons_sc_show_links($sc, $args) {
		trx_addons_get_template_part('templates/tpl.sc_links.php',
										'trx_addons_args_sc_show_links',
										compact('sc', 'args')
									);
	}
}

// Show post meta block: post date, author, categories, counters, etc.
if ( !function_exists('trx_addons_sc_show_post_meta') ) {
	function trx_addons_sc_show_post_meta($sc, $args=array()) {
		$args = array_merge(array(
			'components' => '',	//categories,tags,date,author,counters,share,edit
			'counters' => '',
			'seo' => false,
			'echo' => true
			), $args);
		if (($meta = apply_filters('trx_addons_filter_post_meta', '', array_merge($args, array('echo'=>false)))) != '') {
			if (!empty($args['echo'])) trx_addons_show_layout($meta);
			else return $meta;
		} else {
			if (empty($args['echo'])) ob_start();
			trx_addons_get_template_part('templates/tpl.sc_post_meta.php',
											'trx_addons_args_sc_show_post_meta',
											compact('sc', 'args')
										);
			if (empty($args['echo'])) {
				$meta = ob_get_contents();
				ob_end_clean();
				return $meta;
			}
		}
	}
}

// Display begin of the slider layout for some shortcodes
if (!function_exists('trx_addons_sc_show_slider_wrap_start')) {
	function trx_addons_sc_show_slider_wrap_start($sc, $args) {
		trx_addons_get_template_part('templates/tpl.sc_slider_start.php',
										'trx_addons_args_sc_show_slider_wrap',
										apply_filters('trx_addons_filter_sc_show_slider_args', compact('sc', 'args'))
									);
	}
}

// Display end of the slider layout for some shortcodes
if (!function_exists('trx_addons_sc_show_slider_wrap_end')) {
	function trx_addons_sc_show_slider_wrap_end($sc, $args) {
		trx_addons_get_template_part('templates/tpl.sc_slider_end.php',
										'trx_addons_args_sc_show_slider_wrap', 
										apply_filters('trx_addons_filter_sc_show_slider_args', compact('sc', 'args'))
									);
	}
}


// Shortcode's common params for Visual Composer
//---------------------------------------------------------

// Return ID, Class, CSS params
if ( !function_exists( 'trx_addons_vc_add_id_param' ) ) {
	function trx_addons_vc_add_id_param($group=false) {
		$params = array(
					array(
						"param_name" => "id",
						"heading" => esc_html__("Element ID", 'trx_addons'),
						"description" => wp_kses_data( __("ID for current element", 'trx_addons') ),
						"admin_label" => true,
						"type" => "textfield"
					),
					array(
						"param_name" => "class",
						"heading" => esc_html__("Element CSS class", 'trx_addons'),
						"description" => wp_kses_data( __("CSS class for current element", 'trx_addons') ),
						"admin_label" => true,
						"type" => "textfield"
					),
					array(
						'param_name' => 'css',
						'heading' => __( 'CSS box', 'trx_addons' ),
						'group' => __( 'Design Options', 'trx_addons' ),
						'type' => 'css_editor'
					)
				);

		// Add param 'group' if not empty
		if ($group===false)
			$group = esc_html__('ID &amp; Class', 'trx_addons');
		
		if (!empty($group)) {
			$params[0]['group'] = $group;
			$params[1]['group'] = $group;
		}

		return apply_filters('trx_addons_filter_vc_add_id_param', $params, $group);
	}
}

// Return slider params
if ( !function_exists( 'trx_addons_vc_add_slider_param' ) ) {
	function trx_addons_vc_add_slider_param($group=false) {
		$params = array(
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'trx_addons'),
						"description" => wp_kses_data( __("Show items as slider", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-6 vc_new_row',
						"admin_label" => true,
						"std" => "0",
						"value" => array(esc_html__("Slider", 'trx_addons') => "1" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Space", 'trx_addons'),
						"description" => wp_kses_data( __("Space between slides", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-6',
						'dependency' => array(
							'element' => 'slider',
							'value' => '1'
						),
						"std" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "slider_controls",
						"heading" => esc_html__("Slider controls", 'trx_addons'),
						"description" => wp_kses_data( __("Show arrows in the slider", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-6 vc_new_row',
						'dependency' => array(
							'element' => 'slider',
							'value' => '1'
						),
						"std" => "none",
						"value" => array_flip(trx_addons_get_list_sc_slider_controls()),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider_pagination",
						"heading" => esc_html__("Slider pagination", 'trx_addons'),
						"description" => wp_kses_data( __("Show bullets in the slider", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-6',
						'dependency' => array(
							'element' => 'slider',
							'value' => '1'
						),
						"std" => "none",
						"value" => array_flip(trx_addons_get_list_sc_slider_paginations()),
						"type" => "dropdown"
					)
				);

		// Add param 'group' if not empty
		if ($group===false)
			$group = esc_html__('Slider', 'trx_addons');
		if (!empty($group))
			foreach ($params as $k=>$v)
				$params[$k]['group'] = $group;

		return apply_filters('trx_addons_filter_vc_add_slider_param', $params, $group);
	}
}

// Return title params
if ( !function_exists( 'trx_addons_vc_add_title_param' ) ) {
	function trx_addons_vc_add_title_param($group=false, $button=true) {
		$params = array(
					array(
						"param_name" => "title_style",
						"heading" => esc_html__("Title style", 'trx_addons'),
						"description" => wp_kses_data( __("Select style of the title and subtitle", 'trx_addons') ),
						"admin_label" => true,
						'edit_field_class' => 'vc_col-sm-4',
						"std" => "default",
				        'save_always' => true,
						"value" => array_flip(apply_filters('trx_addons_sc_type', trx_addons_components_get_allowed_layouts('sc', 'title'), 'trx_sc_title')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title_tag",
						"heading" => esc_html__("Title tag", 'trx_addons'),
						"description" => wp_kses_data( __("Select tag (level) of the title", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-4',
						"admin_label" => true,
						"std" => "none",
						"value" => array_flip(trx_addons_get_list_sc_title_tags()),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title_align",
						"heading" => esc_html__("Title alignment", 'trx_addons'),
						"description" => wp_kses_data( __("Select alignment of the title, subtitle and description", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-4',
						"std" => "none",
						"value" => array_flip(trx_addons_get_list_sc_title_aligns()),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'trx_addons'),
						"description" => wp_kses_data( __("Title of the block. Enclose any words in {{ and }} to make them italic or in (( and )) to make them bold. If title style is 'accent' - bolded element styled as shadow, italic - as a filled circle", 'trx_addons') ),
						"admin_label" => true,
						'edit_field_class' => 'vc_col-sm-6',
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", 'trx_addons'),
						"description" => wp_kses_data( __("Subtitle of the block", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-6',
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", 'trx_addons'),
						"description" => wp_kses_data( __("Description of the block", 'trx_addons') ),
						"type" => "textarea_safe"
					),
				);
		
		// Add button's params
		if ($button)
			$params = array_merge($params, array(
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button's URL", 'trx_addons'),
						"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-4',
						"type" => "textfield"
					),
					array(
						"param_name" => "link_text",
						"heading" => esc_html__("Button's text", 'trx_addons'),
						"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-4',
						"type" => "textfield"
					),
					array(
						"param_name" => "link_style",
						"heading" => esc_html__("Button's style", 'trx_addons'),
						"description" => wp_kses_data( __("Select the style (layout) of the button", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-4',
				        'save_always' => true,
						"std" => "default",
						"value" => array_flip(apply_filters('trx_addons_sc_type', trx_addons_components_get_allowed_layouts('sc', 'button'), 'trx_sc_button')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link_image",
						"heading" => esc_html__("Button's image", 'trx_addons'),
						"description" => wp_kses_data( __("Select the promo image from the library for this button", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-4',
						"type" => "attach_image"
					)
				)
			);

		// Add param 'group' if not empty
		if ($group===false)
			$group = esc_html__('Titles', 'trx_addons');
		if (!empty($group))
			foreach ($params as $k=>$v)
				$params[$k]['group'] = $group;

		return apply_filters('trx_addons_filter_vc_add_title_param', $params, $group, $button);
	}
}

// Return query params
if ( !function_exists( 'trx_addons_vc_add_query_param' ) ) {
	function trx_addons_vc_add_query_param($group=false) {
		$params = array(
					array(
						"param_name" => "ids",
						"heading" => esc_html__("IDs to show", 'trx_addons'),
						"description" => wp_kses_data( __("Comma separated IDs list to show. If not empty - parameters 'cat', 'offset' and 'count' are ignored!", 'trx_addons') ),
						"admin_label" => true,
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Count", 'trx_addons'),
						"description" => wp_kses_data( __("Specify number of items to display", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-4',
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"admin_label" => true,
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'trx_addons'),
						"description" => wp_kses_data( __("Specify number of columns. If empty - auto detect by items number", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-4',
						"admin_label" => true,
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset", 'trx_addons'),
						"description" => wp_kses_data( __("Specify number of items to skip before showed items", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-4',
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"admin_label" => true,
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'trx_addons'),
						"description" => wp_kses_data( __("Select how to sort the posts", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-6 vc_new_row',
						"admin_label" => true,
				        'save_always' => true,
						"value" => array_flip(trx_addons_get_list_sc_query_orderby()),
						"std" => "none",
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'trx_addons'),
						"description" => wp_kses_data( __("Select sort order", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-6',
						"value" => array_flip(trx_addons_get_list_sc_query_orders()),
				        'save_always' => true,
						"std" => "asc",
						"type" => "dropdown"
					)
				);

		// Add param 'group' if not empty
		if ($group===false)
			$group = esc_html__('Query', 'trx_addons');
		if (!empty($group))
			foreach ($params as $k=>$v)
				$params[$k]['group'] = $group;

		return apply_filters('trx_addons_filter_vc_add_query_param', $params, $group);
	}
}

// Return hide_on_mobile param
if ( !function_exists( 'trx_addons_vc_add_hide_param' ) ) {
	function trx_addons_vc_add_hide_param($group=false, $hide_on_frontpage=false) {
		$params = array(
					array(
						"param_name" => "hide_on_desktop",
						"heading" => esc_html__("Hide on desktops", 'trx_addons'),
						"description" => wp_kses_data( __("Hide this item on desktops", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-3 vc_new_row',
						"admin_label" => true,
						"std" => "0",
						"value" => array(esc_html__("Hide on desktops", 'trx_addons') => "1" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "hide_on_notebook",
						"heading" => esc_html__("Hide on notebooks", 'trx_addons'),
						"description" => wp_kses_data( __("Hide this item on notebooks", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-3',
						"admin_label" => true,
						"std" => "0",
						"value" => array(esc_html__("Hide on notebooks", 'trx_addons') => "1" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "hide_on_tablet",
						"heading" => esc_html__("Hide on tablets", 'trx_addons'),
						"description" => wp_kses_data( __("Hide this item on tablets", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-3',
						"admin_label" => true,
						"std" => "0",
						"value" => array(esc_html__("Hide on tablets", 'trx_addons') => "1" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "hide_on_mobile",
						"heading" => esc_html__("Hide on mobile devices", 'trx_addons'),
						"description" => wp_kses_data( __("Hide this item on mobile devices", 'trx_addons') ),
						'edit_field_class' => 'vc_col-sm-3',
						"admin_label" => true,
						"std" => "0",
						"value" => array(esc_html__("Hide on mobile devices", 'trx_addons') => "1"),
						"type" => "checkbox"
					)
				);
		if ($hide_on_frontpage) {
			$params[] = array(
				"param_name" => "hide_on_frontpage",
				"heading" => esc_html__("Hide on the Frontpage", 'trx_addons'),
				"description" => wp_kses_data( __("Hide this item on the Frontpage", 'trx_addons') ),
				'edit_field_class' => 'vc_col-sm-3',
				"std" => "0",
				"value" => array(esc_html__("Hide on the Frontpage", 'trx_addons') => "1" ),
				"type" => "checkbox"
			);
		}

		// Add param 'group' if not empty
		if (!empty($group))
			foreach ($params as $k=>$v)
				$params[$k]['group'] = $group;

		return apply_filters('trx_addons_filter_vc_add_hide_param', $params, $group);
	}
}

// Return icon params
if ( !function_exists( 'trx_addons_vc_add_icon_param' ) ) {
	function trx_addons_vc_add_icon_param($group=false, $only_socials=false) {
		if (trx_addons_get_setting('icons_selector') == 'vc') {
			
			// Standard VC icons selector
			$params = array(
						array(
							'type' => 'dropdown',
							'heading' => __( 'Icon library', 'trx_addons' ),
							'edit_field_class' => 'vc_col-sm-4 vc_new_row',
							'value' => array(
								__( 'Font Awesome', 'trx_addons' ) => 'fontawesome',
	/*
								__( 'Open Iconic', 'trx_addons' ) => 'openiconic',
								__( 'Typicons', 'trx_addons' ) => 'typicons',
								__( 'Entypo', 'trx_addons' ) => 'entypo',
								__( 'Linecons', 'trx_addons' ) => 'linecons'
	*/
							),
							'std' => 'fontswesome',
							'param_name' => 'icon_type',
							'description' => __( 'Select icon library.', 'trx_addons' ),
						),
						array(
							'type' => 'iconpicker',
							'heading' => esc_html__( 'Icon', 'trx_addons' ),
							'description' => esc_html__( 'Select icon from library.', 'trx_addons' ),
							'edit_field_class' => 'vc_col-sm-8',
							'param_name' => 'icon_fontawesome',
							'value' => '',
							'settings' => array(
								'emptyIcon' => true,						// default true, display an "EMPTY" icon?
								'iconsPerPage' => 4000,						// default 100, how many icons per/page to display
								'type' => 'fontawesome'
	
							),
							'dependency' => array(
								'element' => 'icon_type',
								'value' => 'fontawesome',
							),
						),
	/*
						array(
							'type' => 'iconpicker',
							'heading' => esc_html__( 'Icon', 'trx_addons' ),
							'description' => esc_html__( 'Select icon from library.', 'trx_addons' ),
							'param_name' => 'icon_openiconic',
							'value' => '',
							'settings' => array(
								'emptyIcon' => true,						// default true, display an "EMPTY" icon?
								'iconsPerPage' => 4000,						// default 100, how many icons per/page to display
								'type' => 'openiconic'
							),
							'dependency' => array(
								'element' => 'icon_type',
								'value' => 'openiconic',
							),
						),
						array(
							'type' => 'iconpicker',
							'heading' => esc_html__( 'Icon', 'trx_addons' ),
							'description' => esc_html__( 'Select icon from library.', 'trx_addons' ),
							'param_name' => 'icon_typicons',
							'value' => '',
							'settings' => array(
								'emptyIcon' => true,						// default true, display an "EMPTY" icon?
								'iconsPerPage' => 4000,						// default 100, how many icons per/page to display
								'type' => 'typicons',
							),
							'dependency' => array(
								'element' => 'icon_type',
								'value' => 'typicons',
							),
						),
						array(
							'type' => 'iconpicker',
							'heading' => esc_html__( 'Icon', 'trx_addons' ),
							'description' => esc_html__( 'Select icon from library.', 'trx_addons' ),
							'param_name' => 'icon_entypo',
							'value' => '',
							'settings' => array(
								'emptyIcon' => true,						// default true, display an "EMPTY" icon?
								'iconsPerPage' => 4000,						// default 100, how many icons per/page to display
								'type' => 'entypo',
							),
							'dependency' => array(
								'element' => 'icon_type',
								'value' => 'entypo',
							),
						),
						array(
							'type' => 'iconpicker',
							'heading' => esc_html__( 'Icon', 'trx_addons' ),
							'description' => esc_html__( 'Select icon from library.', 'trx_addons' ),
							'param_name' => 'icon_linecons',
							'value' => '',
							'settings' => array(
								'emptyIcon' => true,						// default true, display an "EMPTY" icon?
								'iconsPerPage' => 4000,						// default 100, how many icons per/page to display
								'type' => 'linecons',
							),
							'dependency' => array(
								'element' => 'icon_type',
								'value' => 'linecons',
							),
						)
	*/					
					);

		} else {

			// Internal popup with icons list
			$style = $only_socials ? trx_addons_get_setting('socials_type') : trx_addons_get_setting('icons_type');
			$params = array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'trx_addons'),
					"description" => wp_kses_data( __("Select icon", 'trx_addons') ),
					"value" => $style == 'icons' 
									? trx_addons_array_from_list(trx_addons_get_list_icons()) 
									: trx_addons_get_list_files($only_socials ? 'css/socials' : 'css/icons.png', 'png'),
					"std" => "",
					"style" => $style,
					"type" => "icons"
				)
			);
		}
		
		// Add param 'group' if not empty
		if ($group===false)
			$group = esc_html__('Icons', 'trx_addons');
		if (!empty($group))
			foreach ($params as $k=>$v)
				$params[$k]['group'] = $group;

		return apply_filters('trx_addons_filter_vc_add_icon_param', $params, $group, $only_socials);
	}
}





// Shortcode's common params for SOW
//---------------------------------------------------------

// Return ID, Class
if ( !function_exists( 'trx_addons_sow_add_id_param' ) ) {
	function trx_addons_sow_add_id_param($group=false) {
		$params = array(
					// Common VC parameters
					'id' => array(
						"label" => esc_html__("Element ID", 'trx_addons'),
						"type" => "text"
					),
					'class' => array(
						"label" => esc_html__("Element CSS class", 'trx_addons'),
						"type" => "text"
					)
				);

		// Add param 'group' if not empty
		if ($group===false)
			$group = esc_html__('ID &amp; Class', 'trx_addons');
		if (!empty($group))
			$params = array(
				'sow_section_id' => array(
					'label' => $group,
					'hide' => true,
					'fields' => $params,
					'type' => 'section'
					)
				);

		return apply_filters('trx_addons_filter_sow_add_id_param', $params, $group);
	}
}

// Return slider params
if ( !function_exists( 'trx_addons_sow_add_slider_param' ) ) {
	function trx_addons_sow_add_slider_param($group=false, $add_params=array()) {
		$params = array(
					"slider" => array(
						"label" => esc_html__("Slider", 'trx_addons'),
						"default" => false,
						'state_emitter' => array(
							'callback' => 'conditional',
							'args'     => array(
								'use_slider[show]: val',
								'use_slider[hide]: ! val'
							),
						),
						"type" => "checkbox"
					),
					"slides_space" => array(
						"label" => esc_html__("Space", 'trx_addons'),
						"state_handler" => array(
							"use_slider[show]" => array('show'),
							"use_slider[hide]" => array('hide'),
						),
						"type" => "text"
					),
					"slider_controls" => array(
						"label" => esc_html__("Slider controls", 'trx_addons'),
						"state_handler" => array(
							"use_slider[show]" => array('show'),
							"use_slider[hide]" => array('hide'),
						),
						"default" => "none",
						"options" => trx_addons_get_list_sc_slider_controls(),
						"type" => "select"
					),
					"slider_pagination" => array(
						"label" => esc_html__("Slider pagination", 'trx_addons'),
						"state_handler" => array(
							"use_slider[show]" => array('show'),
							"use_slider[hide]" => array('hide'),
						),
						"default" => "none",
						"options" => trx_addons_get_list_sc_slider_paginations(),
						"type" => "select"
					)
				);

		// Additional params / Change default params
		if (is_array($add_params) && count($add_params) > 0) {
			foreach ($add_params as $k=>$v)
				$params[$k] = isset($params[$k]) ? array_merge($params[$k], $v) : $v;
		}

		// Add param 'group' if not empty
		if ($group===false)
			$group = esc_html__('Slider', 'trx_addons');
		if (!empty($group))
			$params = array(
				'sow_section_slider' => array(
					'label' => $group,
					'hide' => true,
					'fields' => $params,
					'type' => 'section'
					)
				);

		return apply_filters('trx_addons_filter_sow_add_slider_param', $params, $group, $add_params);
	}
}

// Return title params
if ( !function_exists( 'trx_addons_sow_add_title_param' ) ) {
	function trx_addons_sow_add_title_param($group=false, $button=true) {
		$params = array(
					"title_style" => array(
						"label" => esc_html__("Title style", 'trx_addons'),
						"default" => "default",
						"options" => apply_filters('trx_addons_sc_type', trx_addons_components_get_allowed_layouts('sc', 'title'), 'trx_sc_title' ),
						"type" => "select"
					),
					"title_tag" => array(
						"label" => esc_html__("Title tag", 'trx_addons'),
						"default" => "none",
						"options" => trx_addons_get_list_sc_title_tags(),
						"type" => "select"
					),
					"title_align" => array(
						"label" => esc_html__("Title alignment", 'trx_addons'),
						"default" => "none",
						"options" => trx_addons_get_list_sc_title_aligns(),
						"type" => "select"
					),
					"title" => array(
						"label" => esc_html__("Title", 'trx_addons'),
						"type" => "text"
					),
					"subtitle" => array(
						"label" => esc_html__("Subtitle", 'trx_addons'),
						"type" => "text"
					),
					"description" => array(
						"label" => esc_html__("Description", 'trx_addons'),
						"type" => "tinymce"
					),
				);
		
		// Add button's params
		if ($button)
			$params = array_merge($params, array(
					"link" => array(
						"label" => esc_html__("Button's URL", 'trx_addons'),
						"type" => "link"
					),
					"link_text" => array(
						"label" => esc_html__("Button's text", 'trx_addons'),
						"type" => "text"
					),
					'link_style' => array(
						'label' => esc_html__("Button's style", 'trx_addons'),
						'default' => 'default',
						'options' => apply_filters('trx_addons_sc_type', trx_addons_components_get_allowed_layouts('sc', 'button'), $this->get_sc_name(), 'sow'),
						'type' => 'select'
					),
					"link_image" => array(
						"label" => esc_html__("Button's image", 'trx_addons'),
						"type" => "media"
					)
				)
			);

		// Add param 'group' if not empty
		if ($group===false)
			$group = esc_html__('Titles', 'trx_addons');
		if (!empty($group))
			$params = array(
				'sow_section_title' => array(
					'label' => $group,
					'hide' => true,
					'fields' => $params,
					'type' => 'section'
					)
				);

		return apply_filters('trx_addons_filter_sow_add_title_param', $params, $group, $button);
	}
}

// Return query params
if ( !function_exists( 'trx_addons_sow_add_query_param' ) ) {
	function trx_addons_sow_add_query_param($group=false, $add_params=array(), $del_params=array()) {
		$params = array(
					"ids" => array(
						"label" => esc_html__("IDs to show", 'trx_addons'),
						'state_emitter' => array(
							'callback' => 'conditional',
							'args'     => array(
								'use_ids[show]: ! val',
								'use_ids[hide]: val'
							)
						),
						"type" => "text"
					),
					"count" => array(
						"label" => esc_html__("Count", 'trx_addons'),
						'state_handler' => array(
							"use_ids[show]" => array('show'),
							"use_ids[hide]" => array('hide')
						),
						"type" => "number"
					),
					"columns" => array(
						"label" => esc_html__("Columns", 'trx_addons'),
						"type" => "number"
					),
					"offset" => array(
						"label" => esc_html__("Offset", 'trx_addons'),
						'state_handler' => array(
							"use_ids[show]" => array('show'),
							"use_ids[hide]" => array('hide')
						),
						"type" => "number"
					),
					"orderby" => array(
						"label" => esc_html__("Order by", 'trx_addons'),
						"options" => trx_addons_get_list_sc_query_orderby(),
						"default" => "none",
						"type" => "select"
					),
					"order" => array(
						"label" => esc_html__("Order", 'trx_addons'),
						"options" => trx_addons_get_list_sc_query_orders(),
						"default" => "asc",
						"type" => "select"
					)
				);

		// Additional params / Change default params
		if (is_array($add_params) && count($add_params) > 0) {
			foreach ($add_params as $k=>$v)
				$params[$k] = array_merge($params[$k], $v);
		}

		// Remove params
		if (is_array($del_params) && count($del_params) > 0) {
			foreach ($del_params as $v)
				if (isset($params[$v])) unset($params[$v]);
		}

		// Add param 'group' if not empty
		if ($group===false)
			$group = esc_html__('Query', 'trx_addons');
		if (!empty($group))
			$params = array(
				'sow_section_query' => array(
					'label' => $group,
					'hide' => true,
					'fields' => $params,
					'type' => 'section'
					)
				);

		return apply_filters('trx_addons_filter_sow_add_query_param', $params, $group, $add_params, $del_params);
	}
}

// Return hide_on_mobile param
if ( !function_exists( 'trx_addons_sow_add_hide_param' ) ) {
	function trx_addons_sow_add_hide_param($group=false, $hide_on_frontpage=false) {
		$params = array(
					"hide_on_desktop" => array(
						"label" => esc_html__("Hide on desktop", 'trx_addons'),
						"description" => wp_kses_data( __("Hide this item on desktops", 'trx_addons') ),
						"type" => "checkbox"
					),
					"hide_on_notebook" => array(
						"label" => esc_html__("Hide on notebooks", 'trx_addons'),
						"description" => wp_kses_data( __("Hide this item on notebooks", 'trx_addons') ),
						"type" => "checkbox"
					),
					"hide_on_tablet" => array(
						"label" => esc_html__("Hide on tablets", 'trx_addons'),
						"description" => wp_kses_data( __("Hide this item on tablets", 'trx_addons') ),
						"type" => "checkbox"
					),
					"hide_on_mobile" => array(
						"label" => esc_html__("Hide on mobile devices", 'trx_addons'),
						"description" => wp_kses_data( __("Hide this item on mobile devices", 'trx_addons') ),
						"type" => "checkbox"
					)
				);
		if ($hide_on_frontpage) {
			$params["hide_on_frontpage"] = array(
						"label" => esc_html__("Hide on Front page", 'trx_addons'),
						"description" => wp_kses_data( __("Hide this item on Front page", 'trx_addons') ),
						"type" => "checkbox"
					);
		}
		
		// Add param 'group' if not empty
		if (!empty($group))
			$params = array(
				'sow_section_hide' => array(
					'label' => $group,
					'hide' => true,
					'fields' => $params,
					'type' => 'section'
					)
				);

		return apply_filters('trx_addons_filter_sow_add_hide_param', $params, $group);
	}
}

// Return icon params
if ( !function_exists( 'trx_addons_sow_add_icon_param' ) ) {
	function trx_addons_sow_add_icon_param($group=false, $only_socials=false) {
		if (trx_addons_get_setting('icons_selector') == 'vc') {
			
			// Standard SOW icons selector
			$params = array(
						'icon' => array(
							'label' => __('Icon', 'trx_addons'),
							"description" => wp_kses_data( __("Select item's icon", 'trx_addons') ),
							'type' => 'icon'
						)
					);

		} else {

			// Internal popup with icons list
			$style = $only_socials ? trx_addons_get_setting('socials_type') : trx_addons_get_setting('icons_type');
			$params = array(
				"icon" => array(
					"label" => esc_html__("Icon", 'trx_addons'),
					"description" => wp_kses_data( __("Select icon", 'trx_addons') ),
					"options" => $style == 'icons' 
									? trx_addons_array_from_list(trx_addons_get_list_icons()) 
									: trx_addons_get_list_files($only_socials ? 'css/socials' : 'css/icons.png', 'png'),
					"style" => $style,
					"type" => "icons"
				)
			);

		}
		
		// Add param 'group' if not empty
		if ($group===false)
			$group = esc_html__('Icons', 'trx_addons');
		if (!empty($group))
			$params = array(
				'sow_section_icon' => array(
					'label' => $group,
					'hide' => true,
					'fields' => $params,
					'type' => 'section'
					)
				);

		return apply_filters('trx_addons_filter_sow_add_icon_param', $params, $group, $only_socials);
	}
}
?>