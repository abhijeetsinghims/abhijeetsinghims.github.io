<?php
/**
 * Shortcode: Display any previously created layout
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.06
 */


// trx_sc_layouts
//-------------------------------------------------------------
/*
[trx_sc_layouts id="unique_id" layout="layout_id"]
*/
if ( !function_exists( 'trx_addons_sc_layouts' ) ) {
	function trx_addons_sc_layouts($atts, $content=null) {	
		$atts = trx_addons_sc_prepare_atts('trx_sc_layouts', $atts, array(
			// Individual params
			"type" => "default",
			"layout" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);

		if (!empty($atts['layout'])) $atts['layout'] = apply_filters('trx_addons_filter_get_translated_layout', $atts['layout']);
	
		ob_start();
		trx_addons_get_template_part(array(
										TRX_ADDONS_PLUGIN_CPT_LAYOUTS_SHORTCODES . 'layouts/tpl.'.trx_addons_esc($atts['type']).'.php',
                                        TRX_ADDONS_PLUGIN_CPT_LAYOUTS_SHORTCODES . 'layouts/tpl.default.php'
                                        ),
                                        'trx_addons_args_sc_layouts',
                                        $atts
                                    );
		$output = ob_get_contents();
		ob_end_clean();
		
		return apply_filters('trx_addons_sc_output', $output, 'trx_sc_layouts', $atts, $content);
	}
}


// Add [trx_sc_layouts] in the VC shortcodes list
if (!function_exists('trx_addons_sc_layouts_add_in_vc')) {
	function trx_addons_sc_layouts_add_in_vc() {

		add_shortcode("trx_sc_layouts", "trx_addons_sc_layouts");

	    if (!trx_addons_exists_visual_composer()) return;

		vc_lean_map( "trx_sc_layouts", 'trx_addons_sc_layouts_add_in_vc_params');
		class WPBakeryShortCode_Trx_Sc_Layouts extends WPBakeryShortCode {}
	}
	add_action('init', 'trx_addons_sc_layouts_add_in_vc', 20);
}


// Return params
if (!function_exists('trx_addons_sc_layouts_add_in_vc_params')) {
	function trx_addons_sc_layouts_add_in_vc_params() {
		// If open params in VC Editor
		list($vc_edit, $vc_params) = trx_addons_get_vc_form_params('trx_sc_layouts');
		$layout = $vc_edit && !empty($vc_params['layout']) ? $vc_params['layout'] : 0;
		return apply_filters('trx_addons_sc_map', array(
				"base" => "trx_sc_layouts",
				"name" => esc_html__("Layouts", 'trx_addons'),
				"description" => wp_kses_data( __("Display previously created layout (header, footer, etc.)", 'trx_addons') ),
				"category" => esc_html__('Layouts', 'trx_addons'),
				"icon" => 'icon_trx_sc_layouts',
				"class" => "trx_sc_layouts",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array_merge(
					array(
						array(
							"param_name" => "type",
							"heading" => esc_html__("Type", 'trx_addons'),
							"description" => wp_kses_data( __("Select shortcodes's type", 'trx_addons') ),
							"std" => "default",
							"value" => array_flip(apply_filters('trx_addons_sc_type', array(
								'default' => esc_html__('Default', 'trx_addons'),
							), 'trx_sc_layouts' )),
							"type" => "dropdown"
						),
						array(
							"param_name" => "layout",
							"heading" => esc_html__("Layout", 'trx_addons'),
							"description" => wp_kses_post( __("Select any previously created layout to insert to this page", 'trx_addons')
															. '<br>'
															. sprintf('<a href="%1$s" class="trx_addons_post_editor'.(intval($layout)==0 ? ' trx_addons_hidden' : '').'" target="_blank">%2$s</a>',
																		admin_url( sprintf( "post.php?post=%d&amp;action=edit", $layout ) ),
																		__("Open selected layout in a new tab to edit", 'trx_addons')
																	)
														),
							"admin_label" => true,
					        'save_always' => true,
							"value" => array_flip(trx_addons_get_list_posts(false, TRX_ADDONS_CPT_LAYOUTS_PT)),
							"type" => "dropdown"
						)
					),
					trx_addons_vc_add_id_param()
				)
			), 'trx_sc_layouts' );
	}
}



// SOW Widget
//------------------------------------------------------
if (class_exists('TRX_Addons_SOW_Widget')) {
	class TRX_Addons_SOW_Widget_Layouts extends TRX_Addons_SOW_Widget {
		
		function __construct() {
			parent::__construct(
				'trx_addons_sow_widget_layouts',
				esc_html__('ThemeREX Layouts', 'trx_addons'),
				array(
					'classname' => 'widget_layouts',
					'description' => __('Display previously created layout (header, footer, etc.)', 'trx_addons')
				),
				array(),
				false,
				TRX_ADDONS_PLUGIN_DIR
			);
	
		}

		// Return array with all widget's fields
		function get_widget_form() {
			// If open params in SOW Editor
			list($vc_edit, $vc_params) = trx_addons_get_sow_form_params('TRX_Addons_SOW_Widget_Layouts');
			$layout = $vc_edit && !empty($vc_params['layout']) ? $vc_params['layout'] : 0;
			return apply_filters('trx_addons_sow_map', array_merge(
				array(
					'type' => array(
						'label' => __('Type', 'trx_addons'),
						"description" => wp_kses_data( __("Select shortcodes's type", 'trx_addons') ),
						'default' => 'default',
						'options' => apply_filters('trx_addons_sc_type', array(
							'default' => esc_html__('Default', 'trx_addons')
						), $this->get_sc_name()),
						'type' => 'select'
					),
					"layout" => array(
						"label" => esc_html__("Layout", 'trx_addons'),
						"description" => wp_kses_post( __("Select any previously created layout to insert to this page", 'trx_addons')
															. '<br>'
															. sprintf('<a href="%1$s" class="trx_addons_post_editor'.(intval($layout)==0 ? ' trx_addons_hidden' : '').'" target="_blank">%2$s</a>',
																		admin_url( sprintf( "post.php?post=%d&amp;action=edit", $layout ) ),
																		__("Open selected layout in a new tab to edit", 'trx_addons')
																	)
														),
						"options" => trx_addons_get_list_posts(false, TRX_ADDONS_CPT_LAYOUTS_PT),
						"type" => "select"
					),
				),
				trx_addons_sow_add_id_param()
			), $this->get_sc_name());
		}

	}
	siteorigin_widget_register('trx_addons_sow_widget_layouts', __FILE__, 'TRX_Addons_SOW_Widget_Layouts');
}
?>