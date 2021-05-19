<?php
/**
 * The default template to display the content of the single post, page or attachment
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

$pathwell_seo = pathwell_is_on(pathwell_get_theme_option('seo_snippets'));
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post_item_single post_type_'.esc_attr(get_post_type()) 
												. ' post_format_'.esc_attr(str_replace('post-format-', '', get_post_format())) 
												);
		if ($pathwell_seo) {
			?> itemscope="itemscope" 
			   itemprop="articleBody" 
			   itemtype="http://schema.org/<?php echo esc_attr(pathwell_get_markup_schema()); ?>" 
			   itemid="<?php echo esc_url(get_the_permalink()); ?>"
			   content="<?php echo esc_attr(get_the_title()); ?>"<?php
		}
?>><?php



	do_action('pathwell_action_before_post_data'); 

	// Structured data snippets
	if ($pathwell_seo)
		get_template_part('templates/seo');

	// Featured image
	if ( pathwell_is_off(pathwell_get_theme_option('hide_featured_on_single'))
			&& !pathwell_sc_layouts_showed('featured') 
			&& strpos(get_the_content(), '[trx_widget_banner]')===false) {
		do_action('pathwell_action_before_post_featured'); 
		pathwell_show_post_featured();
		do_action('pathwell_action_after_post_featured'); 
	} else if (has_post_thumbnail()) {
		?><meta itemprop="image" itemtype="http://schema.org/ImageObject" content="<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id())); ?>"><?php
	}

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


	// Title and post meta
	if ( (!pathwell_sc_layouts_showed('title') || !pathwell_sc_layouts_showed('postmeta')) && !in_array(get_post_format(), array('link', 'aside', 'status', 'quote')) ) {
		do_action('pathwell_action_before_post_title'); 
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			if (!pathwell_sc_layouts_showed('title')) {
				the_title( '<h3 class="post_title entry-title"'.($pathwell_seo ? ' itemprop="headline"' : '').'>', '</h3>' );
			}
			?>
		</div><!-- .post_header -->
		<?php
		do_action('pathwell_action_after_post_title'); 
	}

	do_action('pathwell_action_before_post_content'); 

	// Post content
	?>
	<div class="post_content entry-content" itemprop="mainEntityOfPage">		
		<?php

		// Post meta on the single post			
		?><div class="sc_layouts_title_meta"><?php
			if (!empty($res['date'])){pathwell_show_layout($res['date']); }
			if (!empty($res['likes'])){pathwell_show_layout($res['likes']); }
		?></div><?php

		the_content( );

		if (!empty($res['author']) || !empty($res['views']) || !empty($res['share'])) {
		?>
		<div class="meta_after_content">
			<div class="auth"><?php 
				if (!empty($res['author'])){pathwell_show_layout($res['author']);}
				if (!empty($res['views'])){ pathwell_show_layout($res['views']);}
			?></div>
		</div>
		<?php
		}

		do_action('pathwell_action_before_post_pagination'); 

		wp_link_pages( array(
			'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'pathwell' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'pathwell' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) );

		// Taxonomies and share
		if ( is_single() && !is_attachment() ) {
			
			do_action('pathwell_action_before_post_meta'); 

			if (!empty($res['share'])) {
			?><div class="post_meta post_meta_single"><?php
				
				// Post taxonomies
				the_tags( '<span class="post_meta_item post_tags"><span class="post_meta_label">'.esc_html__('Tags:', 'pathwell').'</span> ', ', ', '</span>' );

				// Share
				if (pathwell_is_on(pathwell_get_theme_option('show_share_links'))) {
					pathwell_show_share_links(array(
							'type' => 'block',
							'caption' => '',
							'before' => '<span class="post_meta_item post_share">',
							'after' => '</span>'
						));
				}
			?></div><?php
			}

			do_action('pathwell_action_after_post_meta'); 
		}




		?>
	</div><!-- .entry-content -->
	

	<?php
	do_action('pathwell_action_after_post_content'); 
	
	?>
</article>
