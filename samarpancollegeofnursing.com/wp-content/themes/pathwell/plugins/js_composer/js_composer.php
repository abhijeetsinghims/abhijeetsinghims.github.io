<?php
/* Visual Composer support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('pathwell_vc_theme_setup9')) {
	add_action( 'after_setup_theme', 'pathwell_vc_theme_setup9', 9 );
	function pathwell_vc_theme_setup9() {
		
		add_filter( 'pathwell_filter_merge_styles',		'pathwell_vc_merge_styles' );

		if (pathwell_exists_visual_composer()) {
	
			// Add/Remove params in the standard VC shortcodes
			//-----------------------------------------------------
			add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,	'pathwell_vc_add_params_classes', 10, 3 );
			add_filter( 'vc_iconpicker-type-fontawesome',	'pathwell_vc_iconpicker_type_fontawesome' );
			
			// Color scheme
			$scheme = array(
				"param_name" => "scheme",
				"heading" => esc_html__("Color scheme", 'pathwell'),
				"description" => wp_kses_data( __("Select color scheme to decorate this block", 'pathwell') ),
				"group" => esc_html__('Colors', 'pathwell'),
				"admin_label" => true,
				"value" => array_flip(pathwell_get_list_schemes(true)),
				"type" => "dropdown"
			);
			$sc_list = apply_filters('pathwell_filter_add_scheme_in_vc', array('vc_section', 'vc_row', 'vc_row_inner', 'vc_column', 'vc_column_inner', 'vc_column_text'));
			foreach ($sc_list as $sc)
				vc_add_param($sc, $scheme);

			// Alter height and hide on mobile for Empty Space
			vc_add_param("vc_empty_space", array(
				"param_name" => "alter_height",
				"heading" => esc_html__("Alter height", 'pathwell'),
				"description" => wp_kses_data( __("Select alternative height instead value from the field above", 'pathwell') ),
				"admin_label" => true,
				"value" => array(
					esc_html__('Tiny', 'pathwell') => 'tiny',
					esc_html__('Small', 'pathwell') => 'small',
					esc_html__('Medium', 'pathwell') => 'medium',
					esc_html__('Large', 'pathwell') => 'large',
					esc_html__('Huge', 'pathwell') => 'huge',
					esc_html__('From the value above', 'pathwell') => 'none'
				),
				"type" => "dropdown"
			));
			
			// Add Narrow style to the Progress bars
			vc_add_param("vc_progress_bar", array(
				"param_name" => "narrow",
				"heading" => esc_html__("Narrow", 'pathwell'),
				"description" => wp_kses_data( __("Use narrow style for the progress bar", 'pathwell') ),
				"std" => 0,
				"value" => array(esc_html__("Narrow style", 'pathwell') => "1" ),
				"type" => "checkbox"
			));
			
			// Add param 'Closeable' to the Message Box
			vc_add_param("vc_message", array(
				"param_name" => "closeable",
				"heading" => esc_html__("Closeable", 'pathwell'),
				"description" => wp_kses_data( __("Add 'Close' button to the message box", 'pathwell') ),
				"std" => 0,
				"value" => array(esc_html__("Closeable", 'pathwell') => "1" ),
				"type" => "checkbox"
			));
		}
		if (is_admin()) {
			add_filter( 'pathwell_filter_tgmpa_required_plugins', 'pathwell_vc_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'pathwell_vc_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('pathwell_filter_tgmpa_required_plugins',	'pathwell_vc_tgmpa_required_plugins');
	function pathwell_vc_tgmpa_required_plugins($list=array()) {
		if (pathwell_storage_isset('required_plugins', 'js_composer')) {
			$path = pathwell_get_file_dir('plugins/js_composer/js_composer.zip');
			if (!empty($path) || pathwell_get_theme_setting('tgmpa_upload')) {
				$list[] = array(
					'name' 		=> pathwell_storage_get_array('required_plugins', 'js_composer'),
					'slug' 		=> 'js_composer',
					'source'	=> !empty($path) ? $path : 'upload://js_composer.zip',
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Check if Visual Composer installed and activated
if ( !function_exists( 'pathwell_exists_visual_composer' ) ) {
	function pathwell_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if Visual Composer in frontend editor mode
if ( !function_exists( 'pathwell_vc_is_frontend' ) ) {
	function pathwell_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
		//return function_exists('vc_is_frontend_editor') && vc_is_frontend_editor();
	}
}
	
// Merge custom styles
if ( !function_exists( 'pathwell_vc_merge_styles' ) ) {
	//Handler of the add_filter('pathwell_filter_merge_styles', 'pathwell_vc_merge_styles');
	function pathwell_vc_merge_styles($list) {
		if (pathwell_exists_visual_composer()) {
			$list[] = 'plugins/js_composer/_js_composer.scss';
		}
		return $list;
	}
}
	
// Add theme icons to the VC iconpicker list
if ( !function_exists( 'pathwell_vc_iconpicker_type_fontawesome' ) ) {
	//Handler of the add_filter( 'vc_iconpicker-type-fontawesome',	'pathwell_vc_iconpicker_type_fontawesome' );
	function pathwell_vc_iconpicker_type_fontawesome($icons) {
		$list = pathwell_get_list_icons();
		if (!is_array($list) || count($list) == 0) return $icons;
		$rez = array();
		foreach ($list as $icon)
			$rez[] = array($icon => str_replace('icon-', '', $icon));
		return array_merge( $icons, array(esc_html__('Theme Icons', 'pathwell') => $rez) );
	}
}



// Shortcodes support
//------------------------------------------------------------------------

// Add params to the standard VC shortcodes
if ( !function_exists( 'pathwell_vc_add_params_classes' ) ) {
	//Handler of the add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'pathwell_vc_add_params_classes', 10, 3 );
	function pathwell_vc_add_params_classes($classes, $sc, $atts) {
		// Add color scheme
		if (in_array($sc, apply_filters('pathwell_filter_add_scheme_in_vc', array('vc_section', 'vc_row', 'vc_row_inner', 'vc_column', 'vc_column_inner', 'vc_column_text')))) {
			if (!empty($atts['scheme']) && !pathwell_is_inherit($atts['scheme']))
				$classes .= ($classes ? ' ' : '') . 'scheme_' . $atts['scheme'];
		}
		// Add other specific classes
		if (in_array($sc, array('vc_empty_space'))) {
			if (!empty($atts['alter_height']) && !pathwell_is_off($atts['alter_height']))
				$classes .= ($classes ? ' ' : '') . 'height_' . $atts['alter_height'];
		} else if (in_array($sc, array('vc_progress_bar'))) {
			if (!empty($atts['narrow']) && (int) $atts['narrow']==1)
				$classes .= ($classes ? ' ' : '') . 'vc_progress_bar_narrow';
		} else if (in_array($sc, array('vc_message'))) {
			if (!empty($atts['closeable']) && (int) $atts['closeable']==1)
				$classes .= ($classes ? ' ' : '') . 'vc_message_box_closeable';
		}
		return $classes;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (pathwell_exists_visual_composer()) { require_once PATHWELL_THEME_DIR . 'plugins/js_composer/js_composer-styles.php'; }
?>