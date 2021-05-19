<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0.06
 */

$pathwell_header_css = '';
$pathwell_header_image = get_header_image();
$pathwell_header_video = pathwell_get_header_video();
if (!empty($pathwell_header_image) && pathwell_trx_addons_featured_image_override(is_singular() || pathwell_storage_isset('blog_archive') || is_category())) {
	$pathwell_header_image = pathwell_get_current_mode_image($pathwell_header_image);
}

$pathwell_header_id = str_replace('header-custom-', '', pathwell_get_theme_option("header_style"));
if ((int) $pathwell_header_id == 0) {
	$pathwell_header_id = pathwell_get_post_id(array(
												'name' => $pathwell_header_id,
												'post_type' => defined('TRX_ADDONS_CPT_LAYOUTS_PT') ? TRX_ADDONS_CPT_LAYOUTS_PT : 'cpt_layouts'
												)
											);
} else {
	$pathwell_header_id = apply_filters('pathwell_filter_get_translated_layout', $pathwell_header_id);
}
$pathwell_header_meta = get_post_meta($pathwell_header_id, 'trx_addons_options', true);

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr($pathwell_header_id); 
				?> top_panel_custom_<?php echo esc_attr(sanitize_title(get_the_title($pathwell_header_id)));
				echo !empty($pathwell_header_image) || !empty($pathwell_header_video) 
					? ' with_bg_image' 
					: ' without_bg_image';
				if ($pathwell_header_video!='') 
					echo ' with_bg_video';
				if ($pathwell_header_image!='') 
					echo ' '.esc_attr(pathwell_add_inline_css_class('background-image: url('.esc_url($pathwell_header_image).');'));
				if (!empty($pathwell_header_meta['margin']) != '') 
					echo ' '.esc_attr(pathwell_add_inline_css_class('margin-bottom: '.esc_attr(pathwell_prepare_css_value($pathwell_header_meta['margin'])).';'));
				if (is_single() && has_post_thumbnail()) 
					echo ' with_featured_image';
				if (pathwell_is_on(pathwell_get_theme_option('header_fullheight'))) 
					echo ' header_fullheight pathwell-full-height';
				if (!pathwell_is_inherit(pathwell_get_theme_option('header_scheme')))
					echo ' scheme_' . esc_attr(pathwell_get_theme_option('header_scheme'));
				?>"><?php

	// Background video
	if (!empty($pathwell_header_video)) {
		get_template_part( 'templates/header-video' );
	}
		
	// Custom header's layout
	do_action('pathwell_action_show_layout', $pathwell_header_id);

	// Header widgets area
	get_template_part( 'templates/header-widgets' );
		
?></header>