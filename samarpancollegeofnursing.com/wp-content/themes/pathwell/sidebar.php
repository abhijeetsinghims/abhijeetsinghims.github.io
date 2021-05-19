<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

if (pathwell_sidebar_present()) {
	ob_start();
	$pathwell_sidebar_name = pathwell_get_theme_option('sidebar_widgets');
	pathwell_storage_set('current_sidebar', 'sidebar');
	if ( is_active_sidebar($pathwell_sidebar_name) ) {
		dynamic_sidebar($pathwell_sidebar_name);
	}
	$pathwell_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($pathwell_out)) {
		$pathwell_sidebar_position = pathwell_get_theme_option('sidebar_position');
		?>
		<div class="sidebar <?php echo esc_attr($pathwell_sidebar_position); ?> widget_area<?php if (!pathwell_is_inherit(pathwell_get_theme_option('sidebar_scheme'))) echo ' scheme_'.esc_attr(pathwell_get_theme_option('sidebar_scheme')); ?>" role="complementary">
			<div class="sidebar_inner">
				<?php
				do_action( 'pathwell_action_before_sidebar' );
				pathwell_show_layout(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $pathwell_out));
				do_action( 'pathwell_action_after_sidebar' );
				?>
			</div><!-- /.sidebar_inner -->
		</div><!-- /.sidebar -->
		<?php
	}
}
?>