<?php
/**
 * The template to display single post
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

get_header();

while ( have_posts() ) { the_post();	

	get_template_part( 'content', get_post_format() );

	// Author bio.
	if ( pathwell_get_theme_option('show_author_info')==1 && is_single() && !is_attachment() && get_the_author_meta( 'description' ) ) {
		do_action('pathwell_action_before_post_author'); 
		get_template_part( 'templates/author-bio' );
		do_action('pathwell_action_after_post_author'); 
	}

	do_action('pathwell_action_after_post_data'); 
	
	// Related posts
	if ((int) pathwell_get_theme_option('show_related_posts') && ($pathwell_related_posts = (int) pathwell_get_theme_option('related_posts')) > 0) {
		pathwell_show_related_posts(array('orderby' => 'rand',
										'posts_per_page' => max(1, min(9, $pathwell_related_posts)),
										'columns' => max(1, min(4, pathwell_get_theme_option('related_columns')))
										),
									pathwell_get_theme_option('related_style')
									);
	}

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
}

get_footer();
?>