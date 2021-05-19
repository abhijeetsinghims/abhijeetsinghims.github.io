<?php
/**
 * The template to display the service's single page
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.4
 */

get_header();

while ( have_posts() ) { the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'services_single itemscope' ); trx_addons_seo_snippets('', 'Article'); ?>>

		<?php do_action('trx_addons_action_before_article', 'services.single'); ?>
		
		<section class="services_page_header">	

			<?php
			// Get post meta: price, icon, etc.
			$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);

			// Image
			if ( !trx_addons_sc_layouts_showed('featured') && has_post_thumbnail() ) {
				?><div class="services_page_featured"><?php
					the_post_thumbnail( trx_addons_get_thumb_size('huge'), trx_addons_seo_image_params(array(
								'alt' => get_the_title()
								))
							);
				?></div><?php
			}
			
			// Title
			if ( !trx_addons_sc_layouts_showed('title') ) {
				?><h2 class="services_page_title"><?php the_title(); ?></h2><?php
			}
			?>

		</section>
		<?php

		// Post content
		?><section class="services_page_content entry-content"<?php trx_addons_seo_snippets('articleBody'); ?>><?php
			the_content( );
		?></section><!-- .entry-content --><?php

		// Link to the product
		if (!empty($meta['product']) && (int) $meta['product'] > 0) {
			?><div class="services_page_buttons">
				<a href="<?php echo esc_url(get_permalink($meta['product'])); ?>" class="sc_button theme_button"><?php esc_html_e('Order now', 'trx_addons'); ?></a>
			</div><?php
		}

		do_action('trx_addons_action_after_article', 'services.single');

	?></article><?php

	// Related items (select dishes with same category)
	$taxonomies = array();
	$terms = get_the_terms(get_the_ID(), TRX_ADDONS_CPT_SERVICES_TAXONOMY);
	if ( !empty( $terms ) ) {
		$taxonomies[TRX_ADDONS_CPT_SERVICES_TAXONOMY] = array();
		foreach( $terms as $term )
			$taxonomies[TRX_ADDONS_CPT_SERVICES_TAXONOMY][] = $term->term_id;
	}

	$trx_addons_related_style   = explode('_', trx_addons_get_option('services_style'));
	$trx_addons_related_type    = $trx_addons_related_style[0];
	$trx_addons_related_columns = empty($trx_addons_related_style[1]) ? 1 : max(1, $trx_addons_related_style[1]);
	
	trx_addons_get_template_part('templates/tpl.posts-related.php',
										'trx_addons_args_related',
										apply_filters('trx_addons_filter_args_related', array(
															'class' => 'services_page_related sc_services sc_services_'.esc_attr($trx_addons_related_type),
															'posts_per_page' => $trx_addons_related_columns,
															'columns' => $trx_addons_related_columns,
															'template' => TRX_ADDONS_PLUGIN_CPT . 'services/tpl.'.trim($trx_addons_related_type).'-item.php',
															'template_args_name' => 'trx_addons_args_sc_services',
															'post_type' => TRX_ADDONS_CPT_SERVICES_PT,
															'taxonomies' => $taxonomies
															)
													)
									);

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
}

get_footer();
?>