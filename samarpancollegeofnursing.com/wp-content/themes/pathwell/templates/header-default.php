<?php
/**
 * The template to display default site header
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

$pathwell_header_css = '';
$pathwell_header_image = get_header_image();
$pathwell_header_video = pathwell_get_header_video();
if (!empty($pathwell_header_image) && pathwell_trx_addons_featured_image_override(is_singular() || pathwell_storage_isset('blog_archive') || is_category())) {
	$pathwell_header_image = pathwell_get_current_mode_image($pathwell_header_image);
}

?><header class="top_panel top_panel_default<?php
					echo !empty($pathwell_header_image) || !empty($pathwell_header_video) ? ' with_bg_image' : ' without_bg_image';
					if ($pathwell_header_video!='') echo ' with_bg_video';
					if ($pathwell_header_image!='') echo ' '.esc_attr(pathwell_add_inline_css_class('background-image: url('.esc_url($pathwell_header_image).');'));
					if (is_single() && has_post_thumbnail()) echo ' with_featured_image';
					if (pathwell_is_on(pathwell_get_theme_option('header_fullheight'))) echo ' header_fullheight pathwell-full-height';
					if (!pathwell_is_inherit(pathwell_get_theme_option('header_scheme')))
						echo ' scheme_' . esc_attr(pathwell_get_theme_option('header_scheme'));
					?>"><?php

	// Background video
	if (!empty($pathwell_header_video)) {
		get_template_part( 'templates/header-video' );
	}
	
	// Main menu
	if (pathwell_get_theme_option("menu_style") == 'top') {
		get_template_part( 'templates/header-navi' );
	}

	// Mobile header
	if (pathwell_is_on(pathwell_get_theme_option("header_mobile_enabled"))) {
		get_template_part( 'templates/header-mobile' );
	}
	
	get_template_part( 'templates/header-title');

	// Header widgets area
	get_template_part( 'templates/header-widgets' );

	// Display featured image in the header on the single posts
	// Comment next line to prevent show featured image in the header area
	// and display it in the post's content
	//get_template_part( 'templates/header-single' );

?></header>