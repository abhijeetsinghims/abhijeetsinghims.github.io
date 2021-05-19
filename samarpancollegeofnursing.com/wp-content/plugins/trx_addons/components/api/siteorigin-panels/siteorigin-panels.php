<?php
/**
 * Plugin support: SiteOrigin Panels
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0.30
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}


// Check if plugin 'SiteOrigin Panels' is installed and activated
// Attention! This function is used in many files and was moved to the api.php
/*
if ( !function_exists( 'trx_addons_exists_sop' ) ) {
	function trx_addons_exists_sop() {
		return class_exists('SiteOrigin_Panels');
	}
}
*/

// Check if plugin 'SiteOrigin Widgets bundle' is installed and activated
if ( !function_exists( 'trx_addons_exists_sow' ) ) {
	function trx_addons_exists_sow() {
		return class_exists('SiteOrigin_Widgets_Bundle');
	}
}


// Generate page content to show layout
//------------------------------------------------------------------------

if ( !function_exists( 'trx_addons_sop_get_layout' ) ) {
	add_filter('trx_addons_filter_sc_layout_content', 'trx_addons_sop_get_layout', 10, 2);
	function trx_addons_sop_get_layout($content, $post_id) {
		// Check if this post has panels_data
		if ( trx_addons_exists_sop() && get_post_meta( $post_id, 'panels_data', true ) ) {
			$panel_content = SiteOrigin_Panels::renderer()->render(
				$post_id,
				// Add CSS if this is not the main single post, this is handled by add_single_css
				$post_id !== get_queried_object_id()
			);
			if ( !empty($panel_content) ) {
				$content = $panel_content;
				// This is an archive page, so try strip out anything after the more text
				if ( ! is_singular() ) {
					if ( preg_match( '/<!--more(.*?)?-->/', $content, $matches ) ) {
						$content = explode( $matches[0], $content, 2 );
						$content = $content[0];
						$content = force_balance_tags( $content );
						if ( ! empty( $matches[1] ) && ! empty( $more_link_text ) ) {
							$more_link_text = strip_tags( wp_kses_no_null( trim( $matches[1] ) ) );
						} else {
							$more_link_text = __( 'Read More', 'trx_addons' );
						}
	
						$more_link = apply_filters( 'the_content_more_link', ' <a href="' . get_permalink() . "#more-{$post->ID}\" class=\"more-link\">$more_link_text</a>", $more_link_text );
						$content .= '<p>' . $more_link . '</p>';
					}
				}
			}
		}
		return $content;
	}
}


// One-click import support
//------------------------------------------------------------------------

// Check plugin in the required plugins
if ( !function_exists( 'trx_addons_sop_importer_required_plugins' ) ) {
	if (is_admin()) add_filter( 'trx_addons_filter_importer_required_plugins',	'trx_addons_sop_importer_required_plugins', 10, 2 );
	function trx_addons_sop_importer_required_plugins($not_installed='', $list='') {
		if (strpos($list, 'siteorigin-panels')!==false && !trx_addons_exists_visual_composer())
			$not_installed .= '<br>' . esc_html__('SiteOrigin Panels', 'trx_addons');
		return $not_installed;
	}
}

// Set plugin's specific importer options
if ( !function_exists( 'trx_addons_sop_importer_set_options' ) ) {
	if (is_admin()) add_filter( 'trx_addons_filter_importer_options',	'trx_addons_sop_importer_set_options' );
	function trx_addons_sop_importer_set_options($options=array()) {
		if ( trx_addons_exists_sop() && in_array('siteorigin-panels', $options['required_plugins']) ) {
			$options['additional_options'][] = 'siteorigin_panels_settings';
			$options['additional_options'][] = 'siteorigin_widgets_active';
		}
		return $options;
	}
}


// Class to create our widgets
//------------------------------------------------------------------------
if (trx_addons_exists_sop() && trx_addons_exists_sow()) {
	abstract class TRX_Addons_SOW_Widget extends SiteOrigin_Widget {

		protected $sc_name = '';

		function __construct($id, $name, $widget_options = array(), $control_options = array(), $form_options = array(), $base_folder = false) {
			$this->sc_name = str_replace(array('trx_addons_widget_', 'trx_addons_sow_widget_'), 'trx_sc_', $id);
			parent::__construct($id, $name, $widget_options, $control_options, $form_options, $base_folder);
		}

		// Return shortcode's name
		function get_sc_name() {
			return $this->sc_name;
		}

		// Return widget's layout
		function get_html_content($instance, $args, $template_vars, $css_name) {
			$output = '';
			$func_name = str_replace('trx_sc_', 'trx_addons_sc_', $this->get_sc_name());
			if (function_exists($func_name)) {
				trx_addons_sc_stack_push('trx_sc_layouts');
				$output = call_user_func($func_name, $this->sc_prepare_atts($instance, $this->sc_name));
				trx_addons_sc_stack_pop();
			}
			return $output;
		}

		// Prepare params for our shortcodes
		protected function sc_prepare_atts($atts, $sc='') {
			if (is_array($atts)) {
				foreach($atts as $k=>$v) {
					// Bubble params from SOW Sections to the root
					if (substr($k, 0, 12) == 'sow_section_') {
						foreach ($v as $k1=>$v1)
							$atts[$k1] = $v1;
					}
					// Add icon_type='sow' if attr 'icon' is present
					if (is_array($v)) {
						foreach ($v as $k1=>$v1) {
							if (is_array($v1)) {
								foreach ($v1 as $k2=>$v2) {
									if ($k2 == 'icon' && trx_addons_is_sow_icon($v2))
										$atts[$k][$k1]['icon_type'] = 'sow';
								}
							} else if ($k1 == 'icon' && trx_addons_is_sow_icon($v1))
								$atts[$k]['icon_type'] = 'sow';
						}
					} else if ($k == 'icon' && trx_addons_is_sow_icon($v))
						$atts['icon_type'] = 'sow';
				}
			}
			return apply_filters('trx_addons_filter_sow_sc_prepare_atts', $atts, $sc);
		}
	}
}

// Check if icon name is from the SOW icons
if ( !function_exists( 'trx_addons_is_sow_icon' ) ) {
	function trx_addons_is_sow_icon($icon) {
		list($family, $icon) = (!empty($icon) && strpos($icon, '-' ) !== false) ? explode( '-', $icon, 2 ) : array('', '');
		return !empty($family) && in_array($family, array(
			'elegantline',
			'fontawesome',
			'genericons',
			'icomoon',
			'typicons',
			'ionicons',
		));
	}
}

// Return SOW form params (if exists)
if ( !function_exists( 'trx_addons_get_sow_form_params' ) ) {
	function trx_addons_get_sow_form_params($widget_class) {
		// If open params in SOW Editor
		$vc_edit = is_admin() && trx_addons_get_value_gp('action')=='so_panels_widget_form' && trx_addons_get_value_gp('widget') == $widget_class;
		$vc_params = $vc_edit && isset($_POST['instance']) ? trx_addons_get_value_gp('instance') : array();
		if (!is_array($vc_params) && substr($vc_params, 0, 1) == '{') $vc_params = json_decode($vc_params, true);
		return array($vc_edit, $vc_params);
	}
}



// Custom param's types for SOW
//-----------------------------------------------------------------------
if (trx_addons_exists_sop() && trx_addons_exists_sow()) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'siteorigin-panels/params/select_dynamic/select_dynamic.php';
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'siteorigin-panels/params/icons/icons.php';
}
?>