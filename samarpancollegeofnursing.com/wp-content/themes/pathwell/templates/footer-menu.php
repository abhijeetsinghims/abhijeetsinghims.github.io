<?php
/**
 * The template to display menu in the footer
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0.10
 */

// Footer menu
$pathwell_menu_footer = pathwell_get_nav_menu(array(
											'location' => 'menu_footer',
											'class' => 'sc_layouts_menu sc_layouts_menu_default'
											));
if (!empty($pathwell_menu_footer)) {
	?>
	<div class="footer_menu_wrap">
		<div class="footer_menu_inner">
			<?php pathwell_show_layout($pathwell_menu_footer); ?>
		</div>
	</div>
	<?php
}
?>