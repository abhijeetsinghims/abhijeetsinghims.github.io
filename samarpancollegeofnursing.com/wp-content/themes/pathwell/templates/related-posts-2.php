<?php
/**
 * The template 'Style 2' to displaying related posts
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

$pathwell_link = get_permalink();
$pathwell_post_format = get_post_format();
$pathwell_post_format = empty($pathwell_post_format) ? 'standard' : str_replace('post-format-', '', $pathwell_post_format);

// Post meta
$pathwell_components = pathwell_array_get_keys_by_value(pathwell_get_theme_option('meta_parts'));
$pathwell_counters = pathwell_array_get_keys_by_value(pathwell_get_theme_option('counters'));
$res = pathwell_get_post_meta_array( array(
    'components' => $pathwell_components,
    'counters' => $pathwell_counters,
    'seo' => false,
    'echo' => false
)
);

?><div id="post-<?php the_ID(); ?>" 
	<?php post_class( 'related_item related_item_style_2 post_format_'.esc_attr($pathwell_post_format) ); ?>><?php
	pathwell_show_post_featured(array(
		'thumb_size' => apply_filters('pathwell_filter_related_thumb_size', pathwell_get_thumb_size( (int) pathwell_get_theme_option('related_posts') == 1 ? 'related' : 'related' )),
		'show_no_image' => pathwell_get_theme_setting('allow_no_image'),
		'singular' => false,
		'post_info' => '<div class="post_header entry-header">'
			. '<div class="post_categories">'.wp_kses_post(pathwell_get_post_categories('')).'</div>'
			. '</div>'
		)
	);
	?><div class="post_header entry-header">
		<h5 class="post_title entry-title"><a href="<?php echo esc_url($pathwell_link); ?>"><?php the_title(); ?></a></h5>
		<?php		
			if (has_excerpt()) {
				?><div class="excerpt"><?php
				the_excerpt();
				?></div><?php
			}
			if ( in_array(get_post_type(), array( 'post', 'attachment' ) ) ) {
				?><span class="post_date"><a href="<?php echo esc_url($pathwell_link); ?>" class="post_meta_item"><?php echo wp_kses_data(pathwell_get_date()); ?></a></span>|<?php
			}
			if (!empty($res['likes'])){pathwell_show_layout($res['likes']);}
		?>		
	</div>
</div>