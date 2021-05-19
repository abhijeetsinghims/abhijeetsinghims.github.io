<?php
/**
 * The template to display the socials in the footer
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0.10
 */


// Socials
if ( pathwell_is_on(pathwell_get_theme_option('socials_in_footer')) && ($pathwell_output = pathwell_get_socials_links()) != '') {
	?>
	<div class="footer_socials_wrap socials_wrap">
		<div class="footer_socials_inner">
			<?php pathwell_show_layout($pathwell_output); ?>
		</div>
	</div>
	<?php
}
?>