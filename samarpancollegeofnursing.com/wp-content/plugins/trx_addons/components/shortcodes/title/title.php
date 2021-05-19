<?php
/**
 * Shortcode: Content container
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.4.3
 */

	
// Merge shortcode's specific styles into single stylesheet
if ( !function_exists( 'trx_addons_sc_title_merge_styles' ) ) {
	add_filter("trx_addons_filter_merge_styles", 'trx_addons_sc_title_merge_styles');
	function trx_addons_sc_title_merge_styles($list) {
		$list[] = TRX_ADDONS_PLUGIN_SHORTCODES . 'title/_title.scss';
		return $list;
	}
}


// trx_sc_title
//-------------------------------------------------------------
/*
[trx_sc_title id="unique_id" title="" subtitle="" description=""]
*/
if ( !function_exists( 'trx_addons_sc_title' ) ) {
	function trx_addons_sc_title($atts, $content=null){	
		$atts = trx_addons_sc_prepare_atts('trx_sc_title', $atts, array(
			// Individual params
			'type' => '',
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_style" => 'default',
			"link_image" => '',
			"link_text" => esc_html__('Learn more', 'trx_addons'),
			"title_align" => "left",
			"title_style" => "default",
			"title_tag" => '',
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);
		
		$output = '';

		if (empty($atts['type'])) $atts['type'] = $atts['title_style'];
		else $atts['title_style'] = $atts['type'];
		
		if (!empty($atts['title']) || !empty($atts['subtitle']) || !empty($atts['description'])) {

			ob_start();
			trx_addons_get_template_part(array(
											TRX_ADDONS_PLUGIN_SHORTCODES . 'title/tpl.'.trx_addons_esc($atts['type']).'.php',
											TRX_ADDONS_PLUGIN_SHORTCODES . 'title/tpl.default.php'
											),
                                            'trx_addons_args_sc_title',
                                            $atts
                                        );
			$output = ob_get_contents();
			ob_end_clean();

		}
		
		return apply_filters('trx_addons_sc_output', $output, 'trx_sc_title', $atts, $content);
	}
}


// Add [trx_sc_content] in the VC shortcodes list
if (!function_exists('trx_addons_sc_title_add_in_vc')) {
	function trx_addons_sc_title_add_in_vc() {
		
		add_shortcode("trx_sc_title", "trx_addons_sc_title");
		
		if (!trx_addons_exists_visual_composer()) return;
		
		vc_lean_map("trx_sc_title", 'trx_addons_sc_title_add_in_vc_params');
		class WPBakeryShortCode_Trx_Sc_Title extends WPBakeryShortCode {}
	}
	add_action('init', 'trx_addons_sc_title_add_in_vc', 20);
}

// Return params
if (!function_exists('trx_addons_sc_title_add_in_vc_params')) {
	function trx_addons_sc_title_add_in_vc_params() {
		return apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_title",
				"name" => esc_html__("Title", 'trx_addons'),
				"description" => wp_kses_data( __("Add title, subtitle and description", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_sc_title',
				"class" => "trx_sc_title",
				'content_element' => true,
				'is_container' => false,
				"show_settings_on_create" => true,
				"params" => array_merge(
					trx_addons_vc_add_title_param(''),
					trx_addons_vc_add_id_param()
				)
			), 'trx_sc_title' );
	}
}




// SOW Widget
//------------------------------------------------------
if (class_exists('TRX_Addons_SOW_Widget')) {
	class TRX_Addons_SOW_Widget_Title extends TRX_Addons_SOW_Widget {
		
		function __construct() {
			parent::__construct(
				'trx_addons_sow_widget_title',
				esc_html__('ThemeREX Title', 'trx_addons'),
				array(
					'classname' => 'widget_title',
					'description' => __('Display title', 'trx_addons')
				),
				array(),
				false,
				TRX_ADDONS_PLUGIN_DIR
			);
	
		}


		// Return array with all widget's fields
		function get_widget_form() {
			return apply_filters('trx_addons_sow_map', array_merge(
				trx_addons_sow_add_title_param(),
				trx_addons_sow_add_id_param()
			), $this->get_sc_name());
		}

	}
	siteorigin_widget_register('trx_addons_sow_widget_title', __FILE__, 'TRX_Addons_SOW_Widget_Title');
}
?>