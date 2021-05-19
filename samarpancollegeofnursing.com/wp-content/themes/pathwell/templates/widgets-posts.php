<?php
/**
 * The template to display posts in widgets and/or in the search results
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

$pathwell_post_id    = get_the_ID();
$pathwell_post_date  = pathwell_get_date();
$pathwell_post_title = get_the_title();
$pathwell_post_link  = get_permalink();
$pathwell_post_author_id   = get_the_author_meta('ID');
$pathwell_post_author_name = get_the_author_meta('display_name');
$pathwell_post_author_url  = get_author_posts_url($pathwell_post_author_id, '');

$pathwell_args = get_query_var('pathwell_args_widgets_posts');
$pathwell_show_date = isset($pathwell_args['show_date']) ? (int) $pathwell_args['show_date'] : 1;
$pathwell_show_image = isset($pathwell_args['show_image']) ? (int) $pathwell_args['show_image'] : 1;
$pathwell_show_author = isset($pathwell_args['show_author']) ? (int) $pathwell_args['show_author'] : 1;
$pathwell_show_counters = isset($pathwell_args['show_counters']) ? (int) $pathwell_args['show_counters'] : 1;
$pathwell_show_categories = isset($pathwell_args['show_categories']) ? (int) $pathwell_args['show_categories'] : 1;

$pathwell_output = pathwell_storage_get('pathwell_output_widgets_posts');

$pathwell_post_counters_output = '';
if ( $pathwell_show_counters ) {
	$pathwell_post_counters_output = '<span class="post_info_item post_info_counters">'
								. pathwell_get_post_counters('comments')
							. '</span>';
}


$pathwell_output .= '<article class="post_item with_thumb">';

if ($pathwell_show_image) {
	$pathwell_post_thumb = get_the_post_thumbnail($pathwell_post_id, pathwell_get_thumb_size('tiny'), array(
		'alt' => get_the_title()
	));
	if ($pathwell_post_thumb) $pathwell_output .= '<div class="post_thumb">' . ($pathwell_post_link ? '<a href="' . esc_url($pathwell_post_link) . '">' : '') . ($pathwell_post_thumb) . ($pathwell_post_link ? '</a>' : '') . '</div>';
}

$pathwell_output .= '<div class="post_content">'
			. ($pathwell_show_categories 
					? '<div class="post_categories">'
						. pathwell_get_post_categories()
						. $pathwell_post_counters_output
						. '</div>' 
					: '')
			. '<h6 class="post_title">' . ($pathwell_post_link ? '<a href="' . esc_url($pathwell_post_link) . '">' : '') . ($pathwell_post_title) . ($pathwell_post_link ? '</a>' : '') . '</h6>'
			. apply_filters('pathwell_filter_get_post_info', 
								'<div class="post_info">'
									. ($pathwell_show_date 
										? '<span class="post_info_item post_info_posted">'
											. ($pathwell_post_link ? '<a href="' . esc_url($pathwell_post_link) . '" class="post_info_date">' : '') 
											. esc_html($pathwell_post_date) 
											. ($pathwell_post_link ? '</a>' : '')
											. '</span>'
										: '')
									. ($pathwell_show_author 
										? '<span class="post_info_item post_info_posted_by">' 
											. esc_html__('by', 'pathwell') . ' ' 
											. ($pathwell_post_link ? '<a href="' . esc_url($pathwell_post_author_url) . '" class="post_info_author">' : '') 
											. esc_html($pathwell_post_author_name) 
											. ($pathwell_post_link ? '</a>' : '') 
											. '</span>'
										: '')
									. (!$pathwell_show_categories && $pathwell_post_counters_output
										? $pathwell_post_counters_output
										: '')
								. '</div>')
		. '</div>'
	. '</article>';
pathwell_storage_set('pathwell_output_widgets_posts', $pathwell_output);
?>