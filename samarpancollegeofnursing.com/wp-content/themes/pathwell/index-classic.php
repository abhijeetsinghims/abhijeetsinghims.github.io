<?php
/**
 * The template for homepage posts with "Classic" style
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

pathwell_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	pathwell_show_layout(get_query_var('blog_archive_start'));

	$pathwell_classes = 'posts_container '
						. (substr(pathwell_get_theme_option('blog_style'), 0, 7) == 'classic' ? 'columns_wrap columns_padding_bottom' : 'masonry_wrap');
	$pathwell_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$pathwell_sticky_out = pathwell_get_theme_option('sticky_style')=='columns' 
							&& is_array($pathwell_stickies) && count($pathwell_stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($pathwell_sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
	if (!$pathwell_sticky_out) {
		if (pathwell_get_theme_option('first_post_large') && !is_paged() && !in_array(pathwell_get_theme_option('body_style'), array('fullwide', 'fullscreen'))) {
			the_post();
			get_template_part( 'content', 'excerpt' );
		}
		
		?><div class="<?php echo esc_attr($pathwell_classes); ?>"><?php
	}
	while ( have_posts() ) { the_post(); 
		if ($pathwell_sticky_out && !is_sticky()) {
			$pathwell_sticky_out = false;
			?></div><div class="<?php echo esc_attr($pathwell_classes); ?>"><?php
		}
		get_template_part( 'content', $pathwell_sticky_out && is_sticky() ? 'sticky' : 'classic' );
	}
	
	?></div><?php

	pathwell_show_pagination();

	pathwell_show_layout(get_query_var('blog_archive_end'));

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>