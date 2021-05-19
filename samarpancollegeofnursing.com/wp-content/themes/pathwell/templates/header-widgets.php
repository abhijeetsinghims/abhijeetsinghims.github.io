<?php
/**
 * The template to display the widgets area in the header
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

// Header sidebar
$pathwell_header_name = pathwell_get_theme_option('header_widgets');
$pathwell_header_present = !pathwell_is_off($pathwell_header_name) && is_active_sidebar($pathwell_header_name);
if ($pathwell_header_present) { 
	pathwell_storage_set('current_sidebar', 'header');
	$pathwell_header_wide = pathwell_get_theme_option('header_wide');
	ob_start();
	if ( is_active_sidebar($pathwell_header_name) ) {
		dynamic_sidebar($pathwell_header_name);
	}
	$pathwell_widgets_output = ob_get_contents();
	ob_end_clean();
	if (!empty($pathwell_widgets_output)) {
		$pathwell_widgets_output = preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $pathwell_widgets_output);
		$pathwell_need_columns = strpos($pathwell_widgets_output, 'columns_wrap')===false;
		if ($pathwell_need_columns) {
			$pathwell_columns = max(0, (int) pathwell_get_theme_option('header_columns'));
			if ($pathwell_columns == 0) $pathwell_columns = min(6, max(1, substr_count($pathwell_widgets_output, '<aside ')));
			if ($pathwell_columns > 1)
				$pathwell_widgets_output = preg_replace("/<aside([^>]*)class=\"widget/", "<aside$1class=\"column-1_".esc_attr($pathwell_columns).' widget', $pathwell_widgets_output);
			else
				$pathwell_need_columns = false;
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo !empty($pathwell_header_wide) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<div class="header_widgets_inner widget_area_inner">
				<?php 
				if (!$pathwell_header_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($pathwell_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'pathwell_action_before_sidebar' );
				pathwell_show_layout($pathwell_widgets_output);
				do_action( 'pathwell_action_after_sidebar' );
				if ($pathwell_need_columns) {
					?></div>	<!-- /.columns_wrap --><?php
				}
				if (!$pathwell_header_wide) {
					?></div>	<!-- /.content_wrap --><?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
?>