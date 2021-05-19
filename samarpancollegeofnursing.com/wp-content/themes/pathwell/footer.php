<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

						// Widgets area inside page content
						pathwell_create_widgets_area('widgets_below_content');
						?>				
					</div><!-- </.content> -->

					<?php
					// Show main sidebar
					get_sidebar();

					// Widgets area below page content
					pathwell_create_widgets_area('widgets_below_page');

					$pathwell_body_style = pathwell_get_theme_option('body_style');
					if ($pathwell_body_style != 'fullscreen') {
						?></div><!-- </.content_wrap> --><?php
					}
					?>
			</div><!-- </.page_content_wrap> -->

			<?php
			// Footer
			$pathwell_footer_type = pathwell_get_theme_option("footer_type");
			if ($pathwell_footer_type == 'custom' && !pathwell_is_layouts_available())
				$pathwell_footer_type = 'default';
			get_template_part( "templates/footer-{$pathwell_footer_type}");
			?>

		</div><!-- /.page_wrap -->

	</div><!-- /.body_wrap -->

	<?php if (pathwell_is_on(pathwell_get_theme_option('debug_mode')) && pathwell_get_file_dir('images/makeup.jpg')!='') { ?>
		<img src="<?php echo esc_url(pathwell_get_file_url('images/makeup.jpg')); ?>" id="makeup">
	<?php } ?>

	<?php wp_footer(); ?>

</body>
</html>