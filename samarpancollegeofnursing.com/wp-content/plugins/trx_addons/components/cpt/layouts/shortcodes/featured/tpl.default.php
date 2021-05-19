<?php
/**
 * The style "default" of the Featured image
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.13
 */

$args = get_query_var('trx_addons_args_sc_layouts_featured');

$need_content = !empty($args['content']);
$need_image = apply_filters('trx_addons_filter_featured_image_override', !get_header_image()
																			&& is_singular()
																			&& in_array(get_post_type(), array('post', 'page'))
																			&& has_post_thumbnail()
							);

if ( $need_content || $need_image )  {
	if ($need_image) {
		$trx_addons_attachment_src = trx_addons_get_current_mode_image();
		if (!empty($trx_addons_attachment_src))
			$args['css'] = 'background-image:url('.esc_url($trx_addons_attachment_src).');' . $args['css'];
		else
			$need_image = false;
	}
	if ( $need_content || $need_image )  {
		if (!empty($args['height']))
			$args['css'] = trx_addons_get_css_dimensions_from_values(array('min-height' => $args['height'])) . ';' . $args['css'];
		?><div<?php if (!empty($args['id'])) echo ' id="'.esc_attr($args['id']).'"'; ?> class="sc_layouts_featured<?php
				trx_addons_cpt_layouts_sc_add_classes($args);
				echo esc_attr($need_content ? ' with' : ' without') . '_content';
				echo esc_attr($need_image ? ' with' : ' without') . '_image';
			?>"<?php
			if (!empty($args['css'])) echo ' style="'.esc_attr($args['css']).'"'; ?>><?php
			
			if ($need_content) trx_addons_show_layout($args['content'], '<div class="sc_layouts_featured_content">', '</div>');

		?></div><!-- /.sc_layouts_featured --><?php

		trx_addons_sc_layouts_showed('featured', $need_image);
	}
}
?>