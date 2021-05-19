<div class="front_page_section front_page_section_googlemap<?php
			$pathwell_scheme = pathwell_get_theme_option('front_page_googlemap_scheme');
			if (!pathwell_is_inherit($pathwell_scheme)) echo ' scheme_'.esc_attr($pathwell_scheme);
			echo ' front_page_section_paddings_'.esc_attr(pathwell_get_theme_option('front_page_googlemap_paddings'));
		?>"<?php
		$pathwell_css = '';
		$pathwell_bg_image = pathwell_get_theme_option('front_page_googlemap_bg_image');
		if (!empty($pathwell_bg_image)) 
			$pathwell_css .= 'background-image: url('.esc_url(pathwell_get_attachment_url($pathwell_bg_image)).');';
		if (!empty($pathwell_css))
			echo ' style="' . esc_attr($pathwell_css) . '"';
?>><?php
	// Add anchor
	$pathwell_anchor_icon = pathwell_get_theme_option('front_page_googlemap_anchor_icon');	
	$pathwell_anchor_text = pathwell_get_theme_option('front_page_googlemap_anchor_text');	
	if ((!empty($pathwell_anchor_icon) || !empty($pathwell_anchor_text)) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="front_page_section_googlemap"'
										. (!empty($pathwell_anchor_icon) ? ' icon="'.esc_attr($pathwell_anchor_icon).'"' : '')
										. (!empty($pathwell_anchor_text) ? ' title="'.esc_attr($pathwell_anchor_text).'"' : '')
										. ']');
	}
	?>
	<div class="front_page_section_inner front_page_section_googlemap_inner<?php
			if (pathwell_get_theme_option('front_page_googlemap_fullheight'))
				echo ' pathwell-full-height sc_layouts_flex sc_layouts_columns_middle';
			?>"<?php
			$pathwell_css = '';
			$pathwell_bg_mask = pathwell_get_theme_option('front_page_googlemap_bg_mask');
			$pathwell_bg_color = pathwell_get_theme_option('front_page_googlemap_bg_color');
			if (!empty($pathwell_bg_color) && $pathwell_bg_mask > 0)
				$pathwell_css .= 'background-color: '.esc_attr($pathwell_bg_mask==1
																	? $pathwell_bg_color
																	: pathwell_hex2rgba($pathwell_bg_color, $pathwell_bg_mask)
																).';';
			if (!empty($pathwell_css))
				echo ' style="' . esc_attr($pathwell_css) . '"';
	?>>
		<div class="front_page_section_content_wrap front_page_section_googlemap_content_wrap<?php
			$pathwell_layout = pathwell_get_theme_option('front_page_googlemap_layout');
			if ($pathwell_layout != 'fullwidth')
				echo ' content_wrap';
		?>">
			<?php
			// Content wrap with title and description
			$pathwell_caption = pathwell_get_theme_option('front_page_googlemap_caption');
			$pathwell_description = pathwell_get_theme_option('front_page_googlemap_description');
			if (!empty($pathwell_caption) || !empty($pathwell_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				if ($pathwell_layout == 'fullwidth') {
					?><div class="content_wrap"><?php
				}
					// Caption
					if (!empty($pathwell_caption) || (current_user_can('edit_theme_options') && is_customize_preview())) {
						?><h2 class="front_page_section_caption front_page_section_googlemap_caption front_page_block_<?php echo !empty($pathwell_caption) ? 'filled' : 'empty'; ?>"><?php
							echo wp_kses_post($pathwell_caption);
						?></h2><?php
					}
				
					// Description (text)
					if (!empty($pathwell_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
						?><div class="front_page_section_description front_page_section_googlemap_description front_page_block_<?php echo !empty($pathwell_description) ? 'filled' : 'empty'; ?>"><?php
							echo wp_kses_post(wpautop($pathwell_description));
						?></div><?php
					}
				if ($pathwell_layout == 'fullwidth') {
					?></div><?php
				}
			}

			// Content (text)
			$pathwell_content = pathwell_get_theme_option('front_page_googlemap_content');
			if (!empty($pathwell_content) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				if ($pathwell_layout == 'columns') {
					?><div class="front_page_section_columns front_page_section_googlemap_columns columns_wrap">
						<div class="column-1_3">
					<?php
				} else if ($pathwell_layout == 'fullwidth') {
					?><div class="content_wrap"><?php
				}
	
				?><div class="front_page_section_content front_page_section_googlemap_content front_page_block_<?php echo !empty($pathwell_content) ? 'filled' : 'empty'; ?>"><?php
					echo wp_kses_post($pathwell_content);
				?></div><?php
	
				if ($pathwell_layout == 'columns') {
					?></div><div class="column-2_3"><?php
				} else if ($pathwell_layout == 'fullwidth') {
					?></div><?php
				}
			}
			
			// Widgets output
			?><div class="front_page_section_output front_page_section_googlemap_output"><?php 
				if (is_active_sidebar('front_page_googlemap_widgets')) {
					dynamic_sidebar( 'front_page_googlemap_widgets' );
				} else if (current_user_can( 'edit_theme_options' )) {
					if (!pathwell_exists_trx_addons())
						pathwell_customizer_need_trx_addons_message();
					else
						pathwell_customizer_need_widgets_message('front_page_googlemap_caption', 'ThemeREX Addons - Google map');
				}
			?></div><?php

			if ($pathwell_layout == 'columns' && (!empty($pathwell_content) || (current_user_can('edit_theme_options') && is_customize_preview()))) {
				?></div></div><?php
			}
			?>			
		</div>
	</div>
</div>