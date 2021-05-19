<?php
/**
 * The template to display blog archive
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

/*
Template Name: Blog archive
*/

/**
 * Make page with this template and put it into menu
 * to display posts as blog archive
 * You can setup output parameters (blog style, posts per page, parent category, etc.)
 * in the Theme Options section (under the page content)
 * You can build this page in the WordPress editor or any Page Builder to make custom page layout:
 * just insert %%CONTENT%% in the desired place of content
 */

// Get template page's content
$pathwell_content = '';
$pathwell_blog_archive_mask = '%%CONTENT%%';
$pathwell_blog_archive_subst = sprintf('<div class="blog_archive">%s</div>', $pathwell_blog_archive_mask);
if ( have_posts() ) {
	the_post();
	if (($pathwell_content = apply_filters('the_content', get_the_content())) != '') {
		if (($pathwell_pos = strpos($pathwell_content, $pathwell_blog_archive_mask)) !== false) {
			$pathwell_content = preg_replace('/(\<p\>\s*)?'.$pathwell_blog_archive_mask.'(\s*\<\/p\>)/i', $pathwell_blog_archive_subst, $pathwell_content);
		} else
			$pathwell_content .= $pathwell_blog_archive_subst;
		$pathwell_content = explode($pathwell_blog_archive_mask, $pathwell_content);
		// Add VC custom styles to the inline CSS
		$vc_custom_css = get_post_meta( get_the_ID(), '_wpb_shortcodes_custom_css', true );
		if ( !empty( $vc_custom_css ) ) pathwell_add_inline_css(strip_tags($vc_custom_css));
	}
}

// Prepare args for a new query
$pathwell_args = array(
	'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish'
);
$pathwell_args = pathwell_query_add_posts_and_cats($pathwell_args, '', pathwell_get_theme_option('post_type'), pathwell_get_theme_option('parent_cat'));
$pathwell_page_number = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
if ($pathwell_page_number > 1) {
	$pathwell_args['paged'] = $pathwell_page_number;
	$pathwell_args['ignore_sticky_posts'] = true;
}
$pathwell_ppp = pathwell_get_theme_option('posts_per_page');
if ((int) $pathwell_ppp != 0)
	$pathwell_args['posts_per_page'] = (int) $pathwell_ppp;
// Make a new main query
$GLOBALS['wp_the_query']->query($pathwell_args);


// Add internal query vars in the new query!
if (is_array($pathwell_content) && count($pathwell_content) == 2) {
	set_query_var('blog_archive_start', $pathwell_content[0]);
	set_query_var('blog_archive_end', $pathwell_content[1]);
}

get_template_part('index');
?>