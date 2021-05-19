<?php
/**
 * The template to display default site footer
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0.10
 */

$pathwell_footer_id = str_replace('footer-custom-', '', pathwell_get_theme_option("footer_style"));
if ((int) $pathwell_footer_id == 0) {
	$pathwell_footer_id = pathwell_get_post_id(array(
												'name' => $pathwell_footer_id,
												'post_type' => defined('TRX_ADDONS_CPT_LAYOUTS_PT') ? TRX_ADDONS_CPT_LAYOUTS_PT : 'cpt_layouts'
												)
											);
} else {
	$pathwell_footer_id = apply_filters('pathwell_filter_get_translated_layout', $pathwell_footer_id);
}
$pathwell_footer_meta = get_post_meta($pathwell_footer_id, 'trx_addons_options', true);
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr($pathwell_footer_id); 
						?> footer_custom_<?php echo esc_attr(sanitize_title(get_the_title($pathwell_footer_id))); 
						if (!empty($pathwell_footer_meta['margin']) != '') 
							echo ' '.esc_attr(pathwell_add_inline_css_class('margin-top: '.pathwell_prepare_css_value($pathwell_footer_meta['margin']).';'));
						if (!pathwell_is_inherit(pathwell_get_theme_option('footer_scheme')))
							echo ' scheme_' . esc_attr(pathwell_get_theme_option('footer_scheme'));
						?>">
	<?php
    // Custom footer's layout
    do_action('pathwell_action_show_layout', $pathwell_footer_id);
	?>
</footer><!-- /.footer_wrap -->
