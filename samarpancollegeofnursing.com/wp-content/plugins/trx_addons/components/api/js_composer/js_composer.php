<?php
/**
 * Plugin support: Visual Composer
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}


// Check if Visual Composer installed and activated
// Attention! This function is used in many files and was moved to the api.php
/*
if ( !function_exists( 'trx_addons_exists_visual_composer' ) ) {
	function trx_addons_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}
*/

// Check if Visual Composer in frontend editor mode
if ( !function_exists( 'trx_addons_vc_is_frontend' ) ) {
	function trx_addons_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
		//return function_exists('vc_is_frontend_editor') && vc_is_frontend_editor();
	}
}

// Add new param's option to the specified param
if ( !function_exists( 'trx_addons_vc_add_param_option' ) ) {
	function trx_addons_vc_add_param_option($params, $param_name, $option) {
		if (is_array($params)) {
			foreach($params as $k=>$v) {
				if (isset($v['param_name']) && $v['param_name']==$param_name) {
					$params[$k] = array_merge($v, $option);
					break;
				}
			}
		}
		return $params;
	}
}

// Delete param from list
if ( !function_exists( 'trx_addons_vc_remove_param' ) ) {
	function trx_addons_vc_remove_param($params, $param_name) {
		if (is_array($params)) {
			foreach($params as $k=>$v) {
				if (isset($v['param_name']) && $v['param_name']==$param_name) {
					unset($params[$k]);
					break;
				}
			}
		}
		return $params;
	}
}

// Add div before vc_new_row in the vc_edit_form
if ( !function_exists( 'trx_addons_vc_edit_form_start' ) ) {
	add_action( 'wp_ajax_vc_edit_form', 'trx_addons_vc_edit_form_start', 0 );
	function trx_addons_vc_edit_form_start() {
		ob_start();
	}
}
if ( !function_exists( 'trx_addons_vc_edit_form_end' ) ) {
	add_action( 'vc_edit_form_fields_after_render', 'trx_addons_vc_edit_form_end');
	function trx_addons_vc_edit_form_end() {
		$output = ob_get_contents();
		ob_end_clean();
		$output = preg_replace('/(<div[^>]*class="[^"]*vc_new_row)/', '<div class="vc_new_row_before"></div>$1', $output, -1, $count);
		trx_addons_show_layout($output);
	}
}

// Return VC edit form params (if exists)
if ( !function_exists( 'trx_addons_get_vc_form_params' ) ) {
	function trx_addons_get_vc_form_params($sc) {
		$vc_edit = is_admin() && trx_addons_get_value_gp('action')=='vc_edit_form' && trx_addons_get_value_gp('tag') == $sc;
		$vc_params = $vc_edit && isset($_POST['params']) ? $_POST['params'] : array();
		return array($vc_edit, $vc_params);
	}
}

if ( !function_exists( 'trx_addons_vc_edit_form_enqueue_script' ) ) {
	add_filter( 'vc_edit_form_enqueue_script', 'trx_addons_vc_edit_form_enqueue_script' );
	function trx_addons_vc_edit_form_enqueue_script($scripts) {
		$scripts[] = trx_addons_get_file_url(TRX_ADDONS_PLUGIN_API . 'js_composer/js_composer.admin.js') . '?rnd='.mt_rand();
		return $scripts;
	}
}


// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_vc_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_vc_load_scripts_front');
	function trx_addons_vc_load_scripts_front() {
		if (trx_addons_exists_visual_composer() && trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			wp_enqueue_script( 'trx_addons-js_composer', trx_addons_get_file_url(TRX_ADDONS_PLUGIN_API . 'js_composer/js_composer.js'), array('jquery'), null, true );
		}
	}
}

	
// Merge specific styles into single stylesheet
if ( !function_exists( 'trx_addons_vc_merge_styles' ) ) {
	add_filter("trx_addons_filter_merge_styles", 'trx_addons_vc_merge_styles');
	function trx_addons_vc_merge_styles($list) {
		if (trx_addons_exists_visual_composer())
			$list[] = TRX_ADDONS_PLUGIN_API . 'js_composer/_js_composer.scss';
		return $list;
	}
}


// Merge shortcode's specific styles to the single stylesheet (responsive)
if ( !function_exists( 'trx_addons_vc_merge_styles_responsive' ) ) {
	add_filter("trx_addons_filter_merge_styles_responsive", 'trx_addons_vc_merge_styles_responsive');
	function trx_addons_vc_merge_styles_responsive($list) {
		if (trx_addons_exists_visual_composer())
			$list[] = TRX_ADDONS_PLUGIN_API . 'js_composer/_js_composer.responsive.scss';
		return $list;
	}
}

	
// Merge plugin's specific scripts into single file
if ( !function_exists( 'trx_addons_vc_merge_scripts' ) ) {
	add_action("trx_addons_filter_merge_scripts", 'trx_addons_vc_merge_scripts');
	function trx_addons_vc_merge_scripts($list) {
		if (trx_addons_exists_visual_composer())
			$list[] = TRX_ADDONS_PLUGIN_API . 'js_composer/js_composer.js';
		return $list;
	}
}


// Add responsive sizes
if ( !function_exists( 'trx_addons_vc_sass_responsive' ) ) {
	add_filter("trx_addons_filter_sass_responsive", 'trx_addons_vc_sass_responsive', 11);
	function trx_addons_vc_sass_responsive($list) {
		if (!isset($list['md_lg']))
			$list['md_lg'] = array(
									'min' => $list['sm']['max']+1,
									'max' => $list['lg']['max']
									);
		return $list;
	}
}



// Modify VC shortcodes params
//------------------------------------------------------------------------

// Add/Remove VC shortcodes params
if (!function_exists('trx_addons_vc_add_params')) {
	add_action( 'init', 'trx_addons_vc_add_params');
	function trx_addons_vc_add_params() {
		if (trx_addons_exists_visual_composer()) {
			// Add 'Hide on xxx'
			$sc_list = apply_filters('trx_addons_filter_add_hide_in_vc', array('vc_empty_space'));
			foreach ($sc_list as $sc) {
				$params = trx_addons_vc_add_hide_param();
				foreach ($params as $param)
					vc_add_param($sc, $param);
			}
			// Add 'Fix column' to the columns
			$param = array(
				"param_name" => "fix_column",
				"heading" => esc_html__("Fix column", 'trx_addons'),
				"description" => wp_kses_data( __("Fix this column when page scrolling. Attention! At least one column in the row must have a greater height than this column", 'trx_addons') ),
				"group" => esc_html__('Effects', 'trx_addons'),
				'edit_field_class' => 'vc_col-sm-4',
				"std" => "0",
				"value" => array(esc_html__("Fix column", 'trx_addons') => "1" ),
				"type" => "checkbox"
			);
			vc_add_param('vc_column', $param);
			vc_add_param('vc_column_inner', $param);
			// Add 'Shift X' to the columns
			$param = array(
				"param_name" => "shift_x",
				"heading" => esc_html__("The X-axis shift", 'trx_addons'),
				"description" => wp_kses_data( __("Shift this column along the X-axis", 'trx_addons') ),
				"group" => esc_html__('Effects', 'trx_addons'),
				'dependency' => array(
					'element' => 'fix_column',
					'is_empty' => true
				),
				'edit_field_class' => 'vc_col-sm-4',
				"value" => array_flip(trx_addons_get_list_sc_content_shift()),
				"std" => "none",
				"type" => "dropdown"
			);
			vc_add_param('vc_column', $param);
			vc_add_param('vc_column_inner', $param);
			// Add 'Shift Y' to the columns
			$param = array(
				"param_name" => "shift_y",
				"heading" => esc_html__("The Y-axis shift", 'trx_addons'),
				"description" => wp_kses_data( __("Shift this column along the Y-axis", 'trx_addons') ),
				"group" => esc_html__('Effects', 'trx_addons'),
				'dependency' => array(
					'element' => 'fix_column',
					'is_empty' => true
				),
				'edit_field_class' => 'vc_col-sm-4',
				"value" => array_flip(trx_addons_get_list_sc_content_shift()),
				"std" => "none",
				"type" => "dropdown"
			);
			vc_add_param('vc_column', $param);
			vc_add_param('vc_column_inner', $param);
			// Add 'Extra bg' to the columns and Text Block
			$param = array(
				"param_name" => "extra_bg",
				"heading" => esc_html__("Extend background", 'trx_addons'),
				"description" => wp_kses_data( __("Extend background color of the column", 'trx_addons') ),
				"group" => esc_html__('Design Options', 'trx_addons'),
				'edit_field_class' => 'vc_col-sm-6',
				"value" => array_flip(trx_addons_get_list_sc_content_extra_bg()),
				"std" => "none",
				"type" => "dropdown"
			);
			vc_add_param('vc_column', $param);
			vc_add_param('vc_column_inner', $param);
			vc_add_param('vc_column_text', $param);
			// Add 'Bg mask' to the rows and columns and Text Block
			$param = array(
				"param_name" => "extra_bg_mask",
				"heading" => esc_html__("Background mask", 'trx_addons'),
				"description" => wp_kses_data( __("Specify opacity of the background color to use it as mask for the background image", 'trx_addons') ),
				"group" => esc_html__('Design Options', 'trx_addons'),
				'edit_field_class' => 'vc_col-sm-6',
				"value" => array_flip(trx_addons_get_list_sc_content_extra_bg_mask()),
				"std" => "none",
				"type" => "dropdown"
			);
			vc_add_param('vc_row', $param);
			vc_add_param('vc_row_inner', $param);
			vc_add_param('vc_column', $param);
			vc_add_param('vc_column_inner', $param);
			vc_add_param('vc_column_text', $param);
			// Add 'Hide bg image on XXX' to the rows
			$param = array(
				"param_name" => "hide_bg_image_on_tablet",
				"heading" => esc_html__("Hide bg image on tablet", 'trx_addons'),
				"description" => wp_kses_data( __("Hide background image on the tablets", 'trx_addons') ),
				"group" => esc_html__('Design Options', 'trx_addons'),
				'edit_field_class' => 'vc_col-sm-3',
				"std" => "0",
				"value" => array(esc_html__("Hide bg image on tablet", 'trx_addons') => "1" ),
				"type" => "checkbox"
			);
			vc_add_param('vc_row', $param);
			vc_add_param('vc_row_inner', $param);
			vc_add_param('vc_column', $param);
			vc_add_param('vc_column_inner', $param);
			$param = array(
				"param_name" => "hide_bg_image_on_mobile",
				"heading" => esc_html__("Hide bg image on mobile", 'trx_addons'),
				"description" => wp_kses_data( __("Hide background image on the mobile devices", 'trx_addons') ),
				"group" => esc_html__('Design Options', 'trx_addons'),
				'edit_field_class' => 'vc_col-sm-3',
				"std" => "0",
				"value" => array(esc_html__("Hide bg image on mobile", 'trx_addons') => "1" ),
				"type" => "checkbox"
			);
			vc_add_param('vc_row', $param);
			vc_add_param('vc_row_inner', $param);
			vc_add_param('vc_column', $param);
			vc_add_param('vc_column_inner', $param);
		}
	}
}

// Add params to the standard VC shortcodes
if ( !function_exists( 'trx_addons_vc_add_params_classes' ) ) {
	if (trx_addons_exists_visual_composer()) add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'trx_addons_vc_add_params_classes', 10, 3 );
	function trx_addons_vc_add_params_classes($classes, $sc, $atts) {
		// Add 'hide_on_xxx'
		if (in_array($sc, apply_filters('trx_addons_filter_add_hide_in_vc', array('vc_empty_space')))) {
			if (!empty($atts['hide_on_desktop']))	$classes .= ($classes ? ' ' : '') . 'hide_on_desktop';
			if (!empty($atts['hide_on_notebook']))	$classes .= ($classes ? ' ' : '') . 'hide_on_notebook';
			if (!empty($atts['hide_on_tablet']))	$classes .= ($classes ? ' ' : '') . 'hide_on_tablet';
			if (!empty($atts['hide_on_mobile']))	$classes .= ($classes ? ' ' : '') . 'hide_on_mobile';
		}
		// Add 'vc_column_fixed' and 'shift_x/y'
		if (in_array($sc, array('vc_column', 'vc_column_inner'))) {
			if (!empty($atts['fix_column'])) 
				$classes .= ($classes ? ' ' : '') . 'vc_column_fixed';
			if (empty($atts['fix_column']) && !empty($atts['shift_x']) && $atts['shift_x']!='none') 
				$classes .= ($classes ? ' ' : '') . 'sc_shift_x_' . esc_attr($atts['shift_x']);
			if (empty($atts['fix_column']) && !empty($atts['shift_y']) && $atts['shift_y']!='none') 
				$classes .= ($classes ? ' ' : '') . 'sc_shift_y_' . esc_attr($atts['shift_y']);
		}
		// Add 'extra_bg'
		if (in_array($sc, array('vc_column', 'vc_column_inner', 'vc_column_text'))) {
			if (!empty($atts['extra_bg']) && $atts['extra_bg']!='none') 
				$classes .= ($classes ? ' ' : '') . 'sc_extra_bg_' . esc_attr($atts['extra_bg']);
		}
		// Add 'bg_mask'
		if (in_array($sc, array('vc_row', 'vc_row_inner', 'vc_column', 'vc_column_inner', 'vc_column_text'))) {
			if (!empty($atts['extra_bg_mask']) && $atts['extra_bg_mask']!='none') 
				$classes .= ($classes ? ' ' : '') . 'sc_bg_mask_' . esc_attr($atts['extra_bg_mask']);
		}
		// Add 'Hide bg image on XXX'
		if (in_array($sc, array('vc_row', 'vc_row_inner', 'vc_column', 'vc_column_inner'))) {
			if (!empty($atts['hide_bg_image_on_tablet'])) 
				$classes .= ($classes ? ' ' : '') . 'hide_bg_image_on_tablet';
			if (!empty($atts['hide_bg_image_on_mobile'])) 
				$classes .= ($classes ? ' ' : '') . 'hide_bg_image_on_mobile';
		}
		return $classes;
	}
}

// Add background position to the VC css_editor
if ( !function_exists( 'trx_addons_vc_css_editor' ) ) {
	add_filter( 'vc_css_editor', 'trx_addons_vc_css_editor' );
	function trx_addons_vc_css_editor($output) {
		return str_replace(
			'<div class="vc_background-style">',
			'<div class="vc_background-position">'
				. '<select name="background_position" class="vc_background-position">'
					. '<option value="top left">'.esc_html__('Top Left', 'trx_addons').'</option>'
					. '<option value="top center">'.esc_html__('Top Center', 'trx_addons').'</option>'
					. '<option value="top right">'.esc_html__('Top Right', 'trx_addons').'</option>'
					. '<option value="center left">'.esc_html__('Center Left', 'trx_addons').'</option>'
					. '<option value="center">'.esc_html__('Center', 'trx_addons').'</option>'
					. '<option value="center right">'.esc_html__('Center Right', 'trx_addons').'</option>'
					. '<option value="bottom left">'.esc_html__('Bottom Left', 'trx_addons').'</option>'
					. '<option value="bottom center">'.esc_html__('Bottom Center', 'trx_addons').'</option>'
					. '<option value="bottom right">'.esc_html__('Bottom Right', 'trx_addons').'</option>'
				. '</select>'
			. '</div>'
			. '<div class="vc_background-style">',
			$output);
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check plugin in the required plugins
if ( !function_exists( 'trx_addons_vc_importer_required_plugins' ) ) {
	if (is_admin()) add_filter( 'trx_addons_filter_importer_required_plugins',	'trx_addons_vc_importer_required_plugins', 10, 2 );
	function trx_addons_vc_importer_required_plugins($not_installed='', $list='') {
		if (strpos($list, 'js_composer')!==false && !trx_addons_exists_visual_composer())
			$not_installed .= '<br>' . esc_html__('Visual Composer', 'trx_addons');
		return $not_installed;
	}
}

// Set plugin's specific importer options
if ( !function_exists( 'trx_addons_vc_importer_set_options' ) ) {
	if (is_admin()) add_filter( 'trx_addons_filter_importer_options',	'trx_addons_vc_importer_set_options' );
	function trx_addons_vc_importer_set_options($options=array()) {
		if ( trx_addons_exists_visual_composer() && in_array('js_composer', $options['required_plugins']) ) {
			$options['additional_options'][] = 'wpb_js_templates';		// Add slugs to export options for this plugin
		}
		return $options;
	}
}

// Check if the row will be imported
if ( !function_exists( 'trx_addons_vc_importer_check_row' ) ) {
	if (is_admin()) add_filter('trx_addons_filter_importer_import_row', 'trx_addons_vc_importer_check_row', 9, 4);
	function trx_addons_vc_importer_check_row($flag, $table, $row, $list) {
		if ($flag || strpos($list, 'js_composer')===false) return $flag;
		if ( trx_addons_exists_visual_composer() ) {
			if ($table == 'posts')
				$flag = $row['post_type']=='vc_grid_item';
		}
		return $flag;
	}
}



// Custom param's types for VC
//------------------------------------------------------------------------
if (trx_addons_exists_visual_composer()) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'js_composer/params/select/select.php';
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'js_composer/params/radio/radio.php';
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'js_composer/params/icons/icons.php';
}
?>