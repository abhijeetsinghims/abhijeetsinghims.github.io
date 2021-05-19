<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

// Page (category, tag, archive, author) title

if ( pathwell_need_page_title() ) {
	pathwell_sc_layouts_showed('title', true);
	pathwell_sc_layouts_showed('postmeta', true);
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						// Post meta on the single post
						/*
						if ( is_single() )  {
							?><div class="sc_layouts_title_meta"><?php
								pathwell_show_post_meta(apply_filters('pathwell_filter_post_meta_args', array(
									'components' => pathwell_array_get_keys_by_value(pathwell_get_theme_option('meta_parts')),
									'counters' => pathwell_array_get_keys_by_value(pathwell_get_theme_option('counters')),
									'seo' => pathwell_is_on(pathwell_get_theme_option('seo_snippets'))
									), 'header', 1)
								);
							?></div><?php
						}
						*/
						
						// Blog/Post title
						?><div class="sc_layouts_title_title"><?php
							$pathwell_blog_title = pathwell_get_blog_title();
							$pathwell_blog_title_text = $pathwell_blog_title_class = $pathwell_blog_title_link = $pathwell_blog_title_link_text = '';
							if (is_array($pathwell_blog_title)) {
								$pathwell_blog_title_text = $pathwell_blog_title['text'];
								$pathwell_blog_title_class = !empty($pathwell_blog_title['class']) ? ' '.$pathwell_blog_title['class'] : '';
								$pathwell_blog_title_link = !empty($pathwell_blog_title['link']) ? $pathwell_blog_title['link'] : '';
								$pathwell_blog_title_link_text = !empty($pathwell_blog_title['link_text']) ? $pathwell_blog_title['link_text'] : '';
							} else
								$pathwell_blog_title_text = $pathwell_blog_title;
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr($pathwell_blog_title_class); ?>"><?php
								$pathwell_top_icon = pathwell_get_category_icon();
								if (!empty($pathwell_top_icon)) {
									$pathwell_attr = pathwell_getimagesize($pathwell_top_icon);
									?><img src="<?php echo esc_url($pathwell_top_icon); ?>" alt="<?php esc_html__('image', 'pathwell'); ?>" <?php if (!empty($pathwell_attr[3])) pathwell_show_layout($pathwell_attr[3]);?>><?php
								}
								echo wp_kses_data($pathwell_blog_title_text);
							?></h1>
							<?php
							if (!empty($pathwell_blog_title_link) && !empty($pathwell_blog_title_link_text)) {
								?><a href="<?php echo esc_url($pathwell_blog_title_link); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html($pathwell_blog_title_link_text); ?></a><?php
							}
							
							// Category/Tag description
							if ( is_category() || is_tag() || is_tax() ) 
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
		
						?></div><?php
	
						// Breadcrumbs
						?><div class="sc_layouts_title_breadcrumbs"><?php
							do_action( 'pathwell_action_breadcrumbs');
						?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>