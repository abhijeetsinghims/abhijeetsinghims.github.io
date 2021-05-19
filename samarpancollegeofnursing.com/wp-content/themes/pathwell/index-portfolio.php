<?php
/**
 * The template for homepage posts with "Portfolio" style
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

pathwell_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	pathwell_show_layout(get_query_var('blog_archive_start'));

	$pathwell_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$pathwell_sticky_out = pathwell_get_theme_option('sticky_style')=='columns' 
							&& is_array($pathwell_stickies) && count($pathwell_stickies) > 0 && get_query_var( 'paged' ) < 1;
	
	// Show filters
	$pathwell_cat = pathwell_get_theme_option('parent_cat');
	$pathwell_post_type = pathwell_get_theme_option('post_type');
	$pathwell_taxonomy = pathwell_get_post_type_taxonomy($pathwell_post_type);
	$pathwell_show_filters = pathwell_get_theme_option('show_filters');
	$pathwell_tabs = array();
	if (!pathwell_is_off($pathwell_show_filters)) {
		$pathwell_args = array(
			'type'			=> $pathwell_post_type,
			'child_of'		=> $pathwell_cat,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 1,
			'hierarchical'	=> 0,
			'exclude'		=> '',
			'include'		=> '',
			'number'		=> '',
			'taxonomy'		=> $pathwell_taxonomy,
			'pad_counts'	=> false
		);
		$pathwell_portfolio_list = get_terms($pathwell_args);
		if (is_array($pathwell_portfolio_list) && count($pathwell_portfolio_list) > 0) {
			$pathwell_tabs[$pathwell_cat] = esc_html__('All', 'pathwell');
			foreach ($pathwell_portfolio_list as $pathwell_term) {
				if (isset($pathwell_term->term_id)) $pathwell_tabs[$pathwell_term->term_id] = $pathwell_term->name;
			}
		}
	}
	if (count($pathwell_tabs) > 0) {
		$pathwell_portfolio_filters_ajax = true;
		$pathwell_portfolio_filters_active = $pathwell_cat;
		$pathwell_portfolio_filters_id = 'portfolio_filters';
		?>
		<div class="portfolio_filters pathwell_tabs pathwell_tabs_ajax">
			<ul class="portfolio_titles pathwell_tabs_titles">
				<?php
				foreach ($pathwell_tabs as $pathwell_id=>$pathwell_title) {
					?><li><a href="<?php echo esc_url(pathwell_get_hash_link(sprintf('#%s_%s_content', $pathwell_portfolio_filters_id, $pathwell_id))); ?>" data-tab="<?php echo esc_attr($pathwell_id); ?>"><?php echo esc_html($pathwell_title); ?></a></li><?php
				}
				?>
			</ul>
			<?php
			$pathwell_ppp = pathwell_get_theme_option('posts_per_page');
			if (pathwell_is_inherit($pathwell_ppp)) $pathwell_ppp = '';
			foreach ($pathwell_tabs as $pathwell_id=>$pathwell_title) {
				$pathwell_portfolio_need_content = $pathwell_id==$pathwell_portfolio_filters_active || !$pathwell_portfolio_filters_ajax;
				?>
				<div id="<?php echo esc_attr(sprintf('%s_%s_content', $pathwell_portfolio_filters_id, $pathwell_id)); ?>"
					class="portfolio_content pathwell_tabs_content"
					data-blog-template="<?php echo esc_attr(pathwell_storage_get('blog_template')); ?>"
					data-blog-style="<?php echo esc_attr(pathwell_get_theme_option('blog_style')); ?>"
					data-posts-per-page="<?php echo esc_attr($pathwell_ppp); ?>"
					data-post-type="<?php echo esc_attr($pathwell_post_type); ?>"
					data-taxonomy="<?php echo esc_attr($pathwell_taxonomy); ?>"
					data-cat="<?php echo esc_attr($pathwell_id); ?>"
					data-parent-cat="<?php echo esc_attr($pathwell_cat); ?>"
					data-need-content="<?php echo (false===$pathwell_portfolio_need_content ? 'true' : 'false'); ?>"
				>
					<?php
					if ($pathwell_portfolio_need_content) 
						pathwell_show_portfolio_posts(array(
							'cat' => $pathwell_id,
							'parent_cat' => $pathwell_cat,
							'taxonomy' => $pathwell_taxonomy,
							'post_type' => $pathwell_post_type,
							'page' => 1,
							'sticky' => $pathwell_sticky_out
							)
						);
					?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	} else {
		pathwell_show_portfolio_posts(array(
			'cat' => $pathwell_cat,
			'parent_cat' => $pathwell_cat,
			'taxonomy' => $pathwell_taxonomy,
			'post_type' => $pathwell_post_type,
			'page' => 1,
			'sticky' => $pathwell_sticky_out
			)
		);
	}

	pathwell_show_layout(get_query_var('blog_archive_end'));

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>