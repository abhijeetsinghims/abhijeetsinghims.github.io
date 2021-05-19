<?php
/**
 * The Sticky template to display the sticky posts
 *
 * Used for index/archive
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

$pathwell_columns = max(1, min(3, count(get_option( 'sticky_posts' ))));
$pathwell_post_format = get_post_format();
$pathwell_post_format = empty($pathwell_post_format) ? 'standard' : str_replace('post-format-', '', $pathwell_post_format);
$pathwell_animation = pathwell_get_theme_option('blog_animation');

?><div class="column-1_<?php echo esc_attr($pathwell_columns); ?>"><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_sticky post_format_'.esc_attr($pathwell_post_format) ); ?>
	<?php echo (!pathwell_is_off($pathwell_animation) ? ' data-animation="'.esc_attr(pathwell_get_animation_classes($pathwell_animation)).'"' : ''); ?>
	>

	<?php
	if ( is_sticky() && is_home() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	pathwell_show_post_featured(array(
		'thumb_size' => pathwell_get_thumb_size($pathwell_columns==1 ? 'big' : ($pathwell_columns==2 ? 'med' : 'avatar'))
	));

	if ( !in_array($pathwell_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			the_title( sprintf( '<h6 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h6>' );
			// Post meta
			pathwell_show_post_meta(apply_filters('pathwell_filter_post_meta_args', array(), 'sticky', $pathwell_columns));
			?>
		</div><!-- .entry-header -->
		<?php
	}
	?>
</article></div>