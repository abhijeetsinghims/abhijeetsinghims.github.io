<div class="front_page_section front_page_section_about<?php
			$pathwell_scheme = pathwell_get_theme_option('front_page_about_scheme');
			if (!pathwell_is_inherit($pathwell_scheme)) echo ' scheme_'.esc_attr($pathwell_scheme);
			echo ' front_page_section_paddings_'.esc_attr(pathwell_get_theme_option('front_page_about_paddings'));
		?>"<?php
		$pathwell_css = '';
		$pathwell_bg_image = pathwell_get_theme_option('front_page_about_bg_image');
		if (!empty($pathwell_bg_image)) 
			$pathwell_css .= 'background-image: url('.esc_url(pathwell_get_attachment_url($pathwell_bg_image)).');';
		if (!empty($pathwell_css))
			echo ' style="' . esc_attr($pathwell_css) . '"';
?>><?php
	// Add anchor
	$pathwell_anchor_icon = pathwell_get_theme_option('front_page_about_anchor_icon');	
	$pathwell_anchor_text = pathwell_get_theme_option('front_page_about_anchor_text');	
	if ((!empty($pathwell_anchor_icon) || !empty($pathwell_anchor_text)) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="front_page_section_about"'
										. (!empty($pathwell_anchor_icon) ? ' icon="'.esc_attr($pathwell_anchor_icon).'"' : '')
										. (!empty($pathwell_anchor_text) ? ' title="'.esc_attr($pathwell_anchor_text).'"' : '')
										. ']');
	}
	?>
	<div class="front_page_section_inner front_page_section_about_inner<?php
			if (pathwell_get_theme_option('front_page_about_fullheight'))
				echo ' pathwell-full-height sc_layouts_flex sc_layouts_columns_middle';
			?>"<?php
			$pathwell_css = '';
			$pathwell_bg_mask = pathwell_get_theme_option('front_page_about_bg_mask');
			$pathwell_bg_color = pathwell_get_theme_option('front_page_about_bg_color');
			if (!empty($pathwell_bg_color) && $pathwell_bg_mask > 0)
				$pathwell_css .= 'background-color: '.esc_attr($pathwell_bg_mask==1
																	? $pathwell_bg_color
																	: pathwell_hex2rgba($pathwell_bg_color, $pathwell_bg_mask)
																).';';
			if (!empty($pathwell_css))
				echo ' style="' . esc_attr($pathwell_css) . '"';
	?>>
		<div class="front_page_section_content_wrap front_page_section_about_content_wrap content_wrap">
			<?php
			// Caption
			$pathwell_caption = pathwell_get_theme_option('front_page_about_caption');
			if (!empty($pathwell_caption) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><h2 class="front_page_section_caption front_page_section_about_caption front_page_block_<?php echo !empty($pathwell_caption) ? 'filled' : 'empty'; ?>"><?php echo wp_kses_post($pathwell_caption); ?></h2><?php
			}
		
			// Description (text)
			$pathwell_description = pathwell_get_theme_option('front_page_about_description');
			if (!empty($pathwell_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><div class="front_page_section_description front_page_section_about_description front_page_block_<?php echo !empty($pathwell_description) ? 'filled' : 'empty'; ?>"><?php echo wp_kses_post(wpautop($pathwell_description)); ?></div><?php
			}
			
			// Content
			$pathwell_content = pathwell_get_theme_option('front_page_about_content');
			if (!empty($pathwell_content) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><div class="front_page_section_content front_page_section_about_content front_page_block_<?php echo !empty($pathwell_content) ? 'filled' : 'empty'; ?>"><?php
					$pathwell_page_content_mask = '%%CONTENT%%';
					if (strpos($pathwell_content, $pathwell_page_content_mask) !== false) {
						$pathwell_content = preg_replace(
									'/(\<p\>\s*)?'.$pathwell_page_content_mask.'(\s*\<\/p\>)/i',
									sprintf('<div class="front_page_section_about_source">%s</div>',
												apply_filters('the_content', get_the_content())),
									$pathwell_content
									);
					}
					pathwell_show_layout($pathwell_content);
				?></div><?php
			}
			?>
		</div>
	</div>
</div>