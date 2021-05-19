<div class="front_page_section front_page_section_woocommerce<?php
			$pathwell_scheme = pathwell_get_theme_option('front_page_woocommerce_scheme');
			if (!pathwell_is_inherit($pathwell_scheme)) echo ' scheme_'.esc_attr($pathwell_scheme);
			echo ' front_page_section_paddings_'.esc_attr(pathwell_get_theme_option('front_page_woocommerce_paddings'));
		?>"<?php
		$pathwell_css = '';
		$pathwell_bg_image = pathwell_get_theme_option('front_page_woocommerce_bg_image');
		if (!empty($pathwell_bg_image)) 
			$pathwell_css .= 'background-image: url('.esc_url(pathwell_get_attachment_url($pathwell_bg_image)).');';
		if (!empty($pathwell_css))
			echo ' style="' . esc_attr($pathwell_css) . '"';
?>><?php
	// Add anchor
	$pathwell_anchor_icon = pathwell_get_theme_option('front_page_woocommerce_anchor_icon');	
	$pathwell_anchor_text = pathwell_get_theme_option('front_page_woocommerce_anchor_text');	
	if ((!empty($pathwell_anchor_icon) || !empty($pathwell_anchor_text)) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="front_page_section_woocommerce"'
										. (!empty($pathwell_anchor_icon) ? ' icon="'.esc_attr($pathwell_anchor_icon).'"' : '')
										. (!empty($pathwell_anchor_text) ? ' title="'.esc_attr($pathwell_anchor_text).'"' : '')
										. ']');
	}
	?>
	<div class="front_page_section_inner front_page_section_woocommerce_inner<?php
			if (pathwell_get_theme_option('front_page_woocommerce_fullheight'))
				echo ' pathwell-full-height sc_layouts_flex sc_layouts_columns_middle';
			?>"<?php
			$pathwell_css = '';
			$pathwell_bg_mask = pathwell_get_theme_option('front_page_woocommerce_bg_mask');
			$pathwell_bg_color = pathwell_get_theme_option('front_page_woocommerce_bg_color');
			if (!empty($pathwell_bg_color) && $pathwell_bg_mask > 0)
				$pathwell_css .= 'background-color: '.esc_attr($pathwell_bg_mask==1
																	? $pathwell_bg_color
																	: pathwell_hex2rgba($pathwell_bg_color, $pathwell_bg_mask)
																).';';
			if (!empty($pathwell_css))
				echo ' style="' . esc_attr($pathwell_css) . '"';
	?>>
		<div class="front_page_section_content_wrap front_page_section_woocommerce_content_wrap content_wrap woocommerce">
			<?php
			// Content wrap with title and description
			$pathwell_caption = pathwell_get_theme_option('front_page_woocommerce_caption');
			$pathwell_description = pathwell_get_theme_option('front_page_woocommerce_description');
			if (!empty($pathwell_caption) || !empty($pathwell_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				// Caption
				if (!empty($pathwell_caption) || (current_user_can('edit_theme_options') && is_customize_preview())) {
					?><h2 class="front_page_section_caption front_page_section_woocommerce_caption front_page_block_<?php echo !empty($pathwell_caption) ? 'filled' : 'empty'; ?>"><?php
						echo wp_kses_post($pathwell_caption);
					?></h2><?php
				}
			
				// Description (text)
				if (!empty($pathwell_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
					?><div class="front_page_section_description front_page_section_woocommerce_description front_page_block_<?php echo !empty($pathwell_description) ? 'filled' : 'empty'; ?>"><?php
						echo wp_kses_post(wpautop($pathwell_description));
					?></div><?php
				}
			}
		
			// Content (widgets)
			?><div class="front_page_section_output front_page_section_woocommerce_output list_products shop_mode_thumbs"><?php 
				$pathwell_woocommerce_sc = pathwell_get_theme_option('front_page_woocommerce_products');
				if ($pathwell_woocommerce_sc == 'products') {
					$pathwell_woocommerce_sc_ids = pathwell_get_theme_option('front_page_woocommerce_products_per_page');
					$pathwell_woocommerce_sc_per_page = count(explode(',', $pathwell_woocommerce_sc_ids));
				} else {
					$pathwell_woocommerce_sc_per_page = max(1, (int) pathwell_get_theme_option('front_page_woocommerce_products_per_page'));
				}
				$pathwell_woocommerce_sc_columns = max(1, min($pathwell_woocommerce_sc_per_page, (int) pathwell_get_theme_option('front_page_woocommerce_products_columns')));
				echo do_shortcode("[{$pathwell_woocommerce_sc}"
									. ($pathwell_woocommerce_sc == 'products' 
											? ' ids="'.esc_attr($pathwell_woocommerce_sc_ids).'"' 
											: '')
									. ($pathwell_woocommerce_sc == 'product_category' 
											? ' category="'.esc_attr(pathwell_get_theme_option('front_page_woocommerce_products_categories')).'"' 
											: '')
									. ($pathwell_woocommerce_sc != 'best_selling_products' 
											? ' orderby="'.esc_attr(pathwell_get_theme_option('front_page_woocommerce_products_orderby')).'"'
											  . ' order="'.esc_attr(pathwell_get_theme_option('front_page_woocommerce_products_order')).'"' 
											: '')
									. ' per_page="'.esc_attr($pathwell_woocommerce_sc_per_page).'"' 
									. ' columns="'.esc_attr($pathwell_woocommerce_sc_columns).'"' 
									. ']');
			?></div>
		</div>
	</div>
</div>