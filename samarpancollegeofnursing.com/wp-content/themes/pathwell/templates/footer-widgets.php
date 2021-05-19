<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0.10
 */

// Footer sidebar
$pathwell_footer_name = pathwell_get_theme_option('footer_widgets');
$pathwell_footer_present = !pathwell_is_off($pathwell_footer_name) && is_active_sidebar($pathwell_footer_name);
if ($pathwell_footer_present) { 
	pathwell_storage_set('current_sidebar', 'footer');
	$pathwell_footer_wide = pathwell_get_theme_option('footer_wide');
	ob_start();
	if ( is_active_sidebar($pathwell_footer_name) ) {
		dynamic_sidebar($pathwell_footer_name);
	}
	$pathwell_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($pathwell_out)) {
		$pathwell_out = preg_replace("/<\\/aside>[\r\n\s]*<aside/", "</aside><aside", $pathwell_out);
		$pathwell_need_columns = true;	//or check: strpos($pathwell_out, 'columns_wrap')===false;
		if ($pathwell_need_columns) {
			$pathwell_columns = max(0, (int) pathwell_get_theme_option('footer_columns'));
			if ($pathwell_columns == 0) $pathwell_columns = min(4, max(1, substr_count($pathwell_out, '<aside ')));
			if ($pathwell_columns > 1)
				$pathwell_out = preg_replace("/<aside([^>]*)class=\"widget/", "<aside$1class=\"column-1_".esc_attr($pathwell_columns).' widget', $pathwell_out);
			else
				$pathwell_need_columns = false;
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo !empty($pathwell_footer_wide) ? ' footer_fullwidth' : ''; ?> sc_layouts_row sc_layouts_row_type_normal">
			<div class="footer_widgets_inner widget_area_inner">
				<?php 
				if (!$pathwell_footer_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($pathwell_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'pathwell_action_before_sidebar' );
				pathwell_show_layout($pathwell_out);
				do_action( 'pathwell_action_after_sidebar' );
				if ($pathwell_need_columns) {
					?></div><!-- /.columns_wrap --><?php
				}
				if (!$pathwell_footer_wide) {
					?></div><!-- /.content_wrap --><?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
?>