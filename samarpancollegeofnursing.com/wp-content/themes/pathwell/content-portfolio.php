<?php
/**
 * The Portfolio template to display the content
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

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_portfolio_'.esc_attr($pathwell_columns).' post_format_'.esc_attr($pathwell_post_format).(is_sticky() && !is_paged() ? ' sticky' : '') ); ?>
	<?php echo (!pathwell_is_off($pathwell_animation) ? ' data-animation="'.esc_attr(pathwell_get_animation_classes($pathwell_animation)).'"' : ''); ?>>
	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	$pathwell_image_hover = pathwell_get_theme_option('image_hover');
	// Featured image
	pathwell_show_post_featured(array(
		'thumb_size' => pathwell_get_thumb_size(strpos(pathwell_get_theme_option('body_style'), 'full')!==false || $pathwell_columns < 3 
								? 'masonry-big' 
								: 'masonry'),
		'show_no_image' => true,
		'class' => $pathwell_image_hover == 'dots' ? 'hover_with_info' : '',
		'post_info' => $pathwell_image_hover == 'dots' ? '<div class="post_info">'.esc_html(get_the_title()).'</div>' : ''
	));
	?>
</article>