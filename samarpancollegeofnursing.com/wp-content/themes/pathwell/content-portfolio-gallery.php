<?php
/**
 * The Gallery template to display posts
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

$pathwell_blog_style = explode('_', pathwell_get_theme_option('blog_style'));
$pathwell_columns = empty($pathwell_blog_style[1]) ? 2 : max(2, $pathwell_blog_style[1]);
$pathwell_post_format = get_post_format();
$pathwell_post_format = empty($pathwell_post_format) ? 'standard' : str_replace('post-format-', '', $pathwell_post_format);
$pathwell_animation = pathwell_get_theme_option('blog_animation');
$pathwell_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_gallery post_layout_gallery_'.esc_attr($pathwell_columns).' post_format_'.esc_attr($pathwell_post_format) ); ?>
	<?php echo (!pathwell_is_off($pathwell_animation) ? ' data-animation="'.esc_attr(pathwell_get_animation_classes($pathwell_animation)).'"' : ''); ?>
	data-size="<?php if (!empty($pathwell_image[1]) && !empty($pathwell_image[2])) echo intval($pathwell_image[1]) .'x' . intval($pathwell_image[2]); ?>"
	data-src="<?php if (!empty($pathwell_image[0])) echo esc_url($pathwell_image[0]); ?>"
	>

	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	$pathwell_image_hover = 'icon';
	if (in_array($pathwell_image_hover, array('icons', 'zoom'))) $pathwell_image_hover = 'dots';
	$pathwell_components = pathwell_array_get_keys_by_value(pathwell_get_theme_option('meta_parts'));
	$pathwell_counters = pathwell_array_get_keys_by_value(pathwell_get_theme_option('counters'));
	pathwell_show_post_featured(array(
		'hover' => $pathwell_image_hover,
		'thumb_size' => pathwell_get_thumb_size( strpos(pathwell_get_theme_option('body_style'), 'full')!==false || $pathwell_columns < 3 ? 'masonry-big' : 'masonry' ),
		'thumb_only' => true,
		'show_no_image' => true,
		'post_info' => '<div class="post_details">'
							. '<h2 class="post_title"><a href="'.esc_url(get_permalink()).'">'. esc_html(get_the_title()) . '</a></h2>'
							. '<div class="post_description">'
								. (!empty($pathwell_components)
										? pathwell_show_post_meta(apply_filters('pathwell_filter_post_meta_args', array(
											'components' => $pathwell_components,
											'counters' => $pathwell_counters,
											'seo' => false,
											'echo' => false
											), $pathwell_blog_style[0], $pathwell_columns))
										: '')
								. '<div class="post_description_content">'
									. apply_filters('the_excerpt', get_the_excerpt())
								. '</div>'
								. '<a href="'.esc_url(get_permalink()).'" class="theme_button post_readmore"><span class="post_readmore_label">' . esc_html__('Learn more', 'pathwell') . '</span></a>'
							. '</div>'
						. '</div>'
	));
	?>
</article>