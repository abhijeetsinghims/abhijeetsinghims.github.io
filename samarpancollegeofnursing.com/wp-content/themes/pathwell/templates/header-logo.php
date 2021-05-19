<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

$pathwell_args = get_query_var('pathwell_logo_args');

// Site logo
$pathwell_logo_type   = isset($pathwell_args['type']) ? $pathwell_args['type'] : '';
$pathwell_logo_image  = pathwell_get_logo_image($pathwell_logo_type);
$pathwell_logo_text   = pathwell_is_on(pathwell_get_theme_option('logo_text')) ? get_bloginfo( 'name' ) : '';
$pathwell_logo_slogan = get_bloginfo( 'description', 'display' );
if (!empty($pathwell_logo_image) || !empty($pathwell_logo_text)) {
	?><a class="sc_layouts_logo" href="<?php echo is_front_page() ? '/' : esc_url(home_url('/')); ?>"><?php
		if (!empty($pathwell_logo_image)) {
			if (empty($pathwell_logo_type) && function_exists('the_custom_logo') && (int) $pathwell_logo_image > 0) {
				the_custom_logo();
			} else {
				$pathwell_attr = pathwell_getimagesize($pathwell_logo_image);
				echo '<img src="'.esc_url($pathwell_logo_image).'" alt="' . esc_attr__('image', 'pathwell') . '"'.(!empty($pathwell_attr[3]) ? ' '.wp_kses_data($pathwell_attr[3]) : '').'>';
			}
		} else {
			pathwell_show_layout(pathwell_prepare_macros($pathwell_logo_text), '<span class="logo_text">', '</span>');
			pathwell_show_layout(pathwell_prepare_macros($pathwell_logo_slogan), '<span class="logo_slogan">', '</span>');
		}
	?></a><?php
}
?>