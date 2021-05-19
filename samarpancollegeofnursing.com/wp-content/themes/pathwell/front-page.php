<?php
/**
 * The Front Page template file.
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0.31
 */

get_header();

// If front-page is a static page
if (get_option('show_on_front') == 'page') {

	// If Front Page Builder is enabled - display sections
	if (pathwell_is_on(pathwell_get_theme_option('front_page_enabled'))) {

		if ( have_posts() ) the_post();

		$pathwell_sections = pathwell_array_get_keys_by_value(pathwell_get_theme_option('front_page_sections'), 1, false);
		if (is_array($pathwell_sections)) {
			foreach ($pathwell_sections as $pathwell_section) {
				get_template_part("front-page/section", $pathwell_section);
			}
		}
	
	// Else if this page is blog archive
	} else if (is_page_template('blog.php')) {
		get_template_part('blog');

	// Else - display native page content
	} else {
		get_template_part('page');
	}

// Else get index template to show posts
} else {
	get_template_part('index');
}

get_footer();
?>