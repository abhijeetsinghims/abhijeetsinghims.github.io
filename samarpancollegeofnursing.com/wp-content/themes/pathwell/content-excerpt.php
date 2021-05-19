<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress 
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

$pathwell_post_format = get_post_format();
$pathwell_post_format = empty($pathwell_post_format) ? 'standard' : str_replace('post-format-', '', $pathwell_post_format);
$pathwell_animation = pathwell_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_excerpt post_format_'.esc_attr($pathwell_post_format) ); ?>
	<?php echo (!pathwell_is_off($pathwell_animation) ? ' data-animation="'.esc_attr(pathwell_get_animation_classes($pathwell_animation)).'"' : ''); ?>
	><?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	pathwell_show_post_featured(array( 'thumb_size' => pathwell_get_thumb_size( strpos(pathwell_get_theme_option('body_style'), 'full')!==false ? 'full' : 'square' ) ));

	
	?><div class="entry-excerpt"><?php
	// Post meta
	do_action('pathwell_action_before_post_meta');

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
	if (!empty($res['categories'])){pathwell_show_layout($res['categories']);}
	if (!empty($res['date'])){pathwell_show_layout($res['date']);}
	if (!empty($res['likes'])){pathwell_show_layout($res['likes']);}
	if (!empty($res['views'])){pathwell_show_layout($res['views']);}
			
	

	if (get_the_title() != '') {
		?>
		<div class="post_header entry-header">
			<?php
				do_action('pathwell_action_before_post_title'); 
				// Post title
				the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
	    	?>
		</div><!-- .post_header --><?php
	}

	// Post content
	?><div class="post_content entry-content"><?php
		if (pathwell_get_theme_option('blog_content') == 'fullpost') {
			// Post content area
			?><div class="post_content_inner"><?php
				the_content( '' );
			?></div><?php
			// Inner pages
			wp_link_pages( array(
				'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'pathwell' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'pathwell' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );

		} else {

			$pathwell_show_learn_more = !in_array($pathwell_post_format, array('link', 'aside', 'status', 'quote'));

			// Post content area
			?><div class="post_content_inner"><?php
				if (has_excerpt()) {
					the_excerpt();
				} else if (strpos(get_the_content('!--more'), '!--more')!==false) {
					the_content( '' );
				} else if (in_array($pathwell_post_format, array('link', 'aside', 'status'))) {
					the_content();
				} else if ($pathwell_post_format == 'quote') {
					if (($quote = pathwell_get_tag(get_the_content(), '<blockquote>', '</blockquote>'))!='')
						pathwell_show_layout(wpautop($quote));
					else
						the_excerpt();
				} else if (substr(get_the_content(), 0, 1)!='[') {
					the_excerpt();
				}
			?></div><?php

			// Post meta part 2
			
			if (!empty($res['author'])){pathwell_show_layout($res['author']);}
			if (!empty($res['share'])){pathwell_show_layout($res['share']);}


			// More button
			if ( $pathwell_show_learn_more ) {
				?><p><a class="more-link" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Read more', 'pathwell'); ?></a></p><?php
			}

		}
	?></div><!-- .entry-content -->
	</div><!-- .end-entry-content -->
</article>