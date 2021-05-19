<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

$pathwell_blog_style = explode('_', pathwell_get_theme_option('blog_style'));
$pathwell_columns = empty($pathwell_blog_style[1]) ? 1 : max(1, $pathwell_blog_style[1]);
$pathwell_expanded = !pathwell_sidebar_present() && pathwell_is_on(pathwell_get_theme_option('expand_content'));
$pathwell_post_format = get_post_format();
$pathwell_post_format = empty($pathwell_post_format) ? 'standard' : str_replace('post-format-', '', $pathwell_post_format);
$pathwell_animation = pathwell_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_chess post_layout_chess_'.esc_attr($pathwell_columns).' post_format_'.esc_attr($pathwell_post_format) ); ?>
	<?php echo (!pathwell_is_off($pathwell_animation) ? ' data-animation="'.esc_attr(pathwell_get_animation_classes($pathwell_animation)).'"' : ''); ?>>

	<?php
	// Add anchor
	if ($pathwell_columns == 1 && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="post_'.esc_attr(get_the_ID()).'" title="'.esc_attr(get_the_title()).'" icon="'.esc_attr(pathwell_get_post_icon()).'"]');
	}

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	pathwell_show_post_featured( array(
											'class' => $pathwell_columns == 1 ? 'pathwell-full-height' : '',
											'show_no_image' => true,
											'thumb_bg' => true,
											'thumb_size' => pathwell_get_thumb_size(
																	strpos(pathwell_get_theme_option('body_style'), 'full')!==false
																		? ( $pathwell_columns > 1 ? 'huge' : 'original' )
																		: (	$pathwell_columns > 2 ? 'big' : 'huge')
																	)
											) 
										);

	?><div class="post_inner"><div class="post_inner_content"><?php 

		?><div class="post_header entry-header"><?php 
			do_action('pathwell_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
			
			do_action('pathwell_action_before_post_meta'); 

			// Post meta
			$pathwell_components = pathwell_array_get_keys_by_value(pathwell_get_theme_option('meta_parts'));
			$pathwell_counters = pathwell_array_get_keys_by_value(pathwell_get_theme_option('counters'));
			$pathwell_post_meta = empty($pathwell_components) 
										? '' 
										: pathwell_show_post_meta(apply_filters('pathwell_filter_post_meta_args', array(
												'components' => $pathwell_components,
												'counters' => $pathwell_counters,
												'seo' => false,
												'echo' => false
												), $pathwell_blog_style[0], $pathwell_columns)
											);
			pathwell_show_layout($pathwell_post_meta);
		?></div><!-- .entry-header -->
	
		<div class="post_content entry-content">
			<div class="post_content_inner">
				<?php
				$pathwell_show_learn_more = !in_array($pathwell_post_format, array('link', 'aside', 'status', 'quote'));
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
				?>
			</div>
			<?php
			// Post meta
			if (in_array($pathwell_post_format, array('link', 'aside', 'status', 'quote'))) {
				pathwell_show_layout($pathwell_post_meta);
			}
			// More button
			if ( $pathwell_show_learn_more ) {
				?><p><a class="more-link" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Read more', 'pathwell'); ?></a></p><?php
			}
			?>
		</div><!-- .entry-content -->

	</div></div><!-- .post_inner -->

</article>