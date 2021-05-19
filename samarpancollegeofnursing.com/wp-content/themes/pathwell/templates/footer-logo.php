<?php
/**
 * The template to display the site logo in the footer
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0.10
 */

// Logo
if (pathwell_is_on(pathwell_get_theme_option('logo_in_footer'))) {
	$pathwell_logo_image = '';
	if (pathwell_is_on(pathwell_get_theme_option('logo_retina_enabled')) && pathwell_get_retina_multiplier() > 1)
		$pathwell_logo_image = pathwell_get_theme_option( 'logo_footer_retina' );
	if (empty($pathwell_logo_image)) 
		$pathwell_logo_image = pathwell_get_theme_option( 'logo_footer' );
	$pathwell_logo_text   = get_bloginfo( 'name' );
	if (!empty($pathwell_logo_image) || !empty($pathwell_logo_text)) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if (!empty($pathwell_logo_image)) {
					$pathwell_attr = pathwell_getimagesize($pathwell_logo_image);
					echo '<a href="'.esc_url(home_url('/')).'"><img src="'.esc_url($pathwell_logo_image).'" class="logo_footer_image" alt="' . esc_attr__('image', 'pathwell') . '"'.(!empty($pathwell_attr[3]) ? ' ' . wp_kses_data($pathwell_attr[3]) : '').'></a>' ;
				} else if (!empty($pathwell_logo_text)) {
					echo '<h1 class="logo_footer_text"><a href="'.esc_url(home_url('/')).'">' . esc_html($pathwell_logo_text) . '</a></h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
?>