<?php
/**
 * The template 'Style 1' to displaying related posts
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

$pathwell_link = get_permalink();
$pathwell_post_format = get_post_format();
$pathwell_post_format = empty($pathwell_post_format) ? 'standard' : str_replace('post-format-', '', $pathwell_post_format);
?><div id="post-<?php the_ID(); ?>" 
	<?php post_class( 'related_item related_item_style_1 post_format_'.esc_attr($pathwell_post_format) ); ?>><?php
	pathwell_show_post_featured(array(
		'thumb_size' => apply_filters('pathwell_filter_related_thumb_size', pathwell_get_thumb_size( (int) pathwell_get_theme_option('related_posts') == 1 ? 'huge' : 'big' )),
		'show_no_image' => pathwell_get_theme_setting('allow_no_image'),
		'singular' => false,
		'post_info' => '<div class="post_header entry-header">'
							. '<div class="post_categories">'.wp_kses_post(pathwell_get_post_categories('')).'</div>'
							. '<h6 class="post_title entry-title"><a href="'.esc_url($pathwell_link).'">'.esc_html(get_the_title()).'</a></h6>'
							. (in_array(get_post_type(), array('post', 'attachment'))
									? '<span class="post_date"><a href="'.esc_url($pathwell_link).'">'.wp_kses_data(pathwell_get_date()).'</a></span>'
									: '')
						. '</div>'
		)
	);
?></div>