<?php
/**
 * Information about this theme
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0.30
 */


// Redirect to the 'About Theme' page after switch theme
if (!function_exists('pathwell_about_after_switch_theme')) {
	add_action('after_switch_theme', 'pathwell_about_after_switch_theme', 1000);
	function pathwell_about_after_switch_theme() {
		update_option('pathwell_about_page', 1);
	}
}
if ( !function_exists('pathwell_about_after_setup_theme') ) {
	add_action( 'init', 'pathwell_about_after_setup_theme', 1000 );
	function pathwell_about_after_setup_theme() {
		if (get_option('pathwell_about_page') == 1) {
			update_option('pathwell_about_page', 0);
			wp_safe_redirect(admin_url().'themes.php?page=pathwell_about');
			exit();
		}
	}
}


// Add 'About Theme' item in the Appearance menu
if (!function_exists('pathwell_about_add_menu_items')) {
	add_action( 'admin_menu', 'pathwell_about_add_menu_items' );
	function pathwell_about_add_menu_items() {
		$theme = wp_get_theme();
		$theme_name = $theme->name . (PATHWELL_THEME_FREE ? ' ' . esc_html__('Free', 'pathwell') : '');
		add_theme_page(
			// Translators: Add theme name to the page title
			sprintf(esc_html__('About %s', 'pathwell'), $theme_name),	//page_title
			// Translators: Add theme name to the menu title
			sprintf(esc_html__('About %s', 'pathwell'), $theme_name),	//menu_title
			'manage_options',											//capability
			'pathwell_about',											//menu_slug
			'pathwell_about_page_builder',								//callback
			'dashicons-format-status',									//icon
			''															//menu position
		);
	}
}


// Load page-specific scripts and styles
if (!function_exists('pathwell_about_enqueue_scripts')) {
	add_action( 'admin_enqueue_scripts', 'pathwell_about_enqueue_scripts' );
	function pathwell_about_enqueue_scripts() {
		$screen = function_exists('get_current_screen') ? get_current_screen() : false;
		if (is_object($screen) && $screen->id == 'appearance_page_pathwell_about') {
			// Scripts
			wp_enqueue_script( 'jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true );
			
			if (function_exists('pathwell_plugins_installer_enqueue_scripts'))
				pathwell_plugins_installer_enqueue_scripts();
			
			// Styles
			wp_enqueue_style( 'pathwell-icons',  pathwell_get_file_url('css/font-icons/css/fontello-embedded.css'), array(), null );
			if ( ($fdir = pathwell_get_file_url('theme-specific/theme-about/theme-about.css')) != '' )
				wp_enqueue_style( 'pathwell-about',  $fdir, array(), null );
		}
	}
}


// Build 'About Theme' page
if (!function_exists('pathwell_about_page_builder')) {
	function pathwell_about_page_builder() {
		$theme = wp_get_theme();
		?>
		<div class="pathwell_about">
			<div class="pathwell_about_header">
				<div class="pathwell_about_logo"><?php
					$logo = pathwell_get_file_url('theme-specific/theme-about/logo.jpg');
					if (empty($logo)) $logo = pathwell_get_file_url('screenshot.jpg');
					if (!empty($logo)) {
						?><img src="<?php echo esc_url($logo); ?>"><?php
					}
				?></div>
				
				<?php if (PATHWELL_THEME_FREE) { ?>
					<a href="<?php echo esc_url(pathwell_storage_get('theme_download_url')); ?>"
										   target="_blank"
										   class="pathwell_about_pro_link button button-primary"><?php
											esc_html_e('Get PRO version', 'pathwell');
										?></a>
				<?php } ?>
				<h1 class="pathwell_about_title"><?php
					// Translators: Add theme name and version to the 'Welcome' message
					echo esc_html(sprintf(__('Welcome to %1$s %2$s v.%3$s', 'pathwell'),
								$theme->name,
								PATHWELL_THEME_FREE ? __('Free', 'pathwell') : '',
								$theme->version
								));
				?></h1>
				<div class="pathwell_about_description">
					<?php
					if (PATHWELL_THEME_FREE) {
						?><p><?php
							// Translators: Add the download url and the theme name to the message
							echo wp_kses_data(sprintf(__('Now you are using Free version of <a href="%1$s">%2$s Pro Theme</a>.', 'pathwell'),
														esc_url(pathwell_storage_get('theme_download_url')),
														$theme->name
														)
												);
							// Translators: Add the theme name and supported plugins list to the message
							echo '<br>' . wp_kses_data(sprintf(__('This version is SEO- and Retina-ready. It also has a built-in support for parallax and slider with swipe gestures. %1$s Free is compatible with many popular plugins, such as %2$s', 'pathwell'),
														$theme->name,
														pathwell_about_get_supported_plugins()
														)
												);
						?></p>
						<p><?php
							// Translators: Add the download url to the message
							echo wp_kses_data(sprintf(__('We hope you have a great acquaintance with our themes. If you are looking for a fully functional website, you can get the <a href="%s">Pro Version here</a>', 'pathwell'),
														esc_url(pathwell_storage_get('theme_download_url'))
														)
												);
						?></p><?php
					} else {
						?><p><?php
							// Translators: Add the theme name to the message
							echo wp_kses_data(sprintf(__('%s is a Premium WordPress theme. It has a built-in support for parallax, slider with swipe gestures, and is SEO- and Retina-ready', 'pathwell'),
														$theme->name
														)
												);
						?></p>
						<p><?php
							// Translators: Add supported plugins list to the message
							echo wp_kses_data(sprintf(__('The Premium Theme is compatible with many popular plugins, such as %s', 'pathwell'),
														pathwell_about_get_supported_plugins()
														)
												);
						?></p><?php
					}
					?>
				</div>
			</div>
			<div id="pathwell_about_tabs" class="pathwell_tabs pathwell_about_tabs">
				<ul>
					<li><a href="#pathwell_about_section_start"><?php esc_html_e('Getting started', 'pathwell'); ?></a></li>
					<li><a href="#pathwell_about_section_actions"><?php esc_html_e('Recommended actions', 'pathwell'); ?></a></li>
					<?php if (PATHWELL_THEME_FREE) { ?>
						<li><a href="#pathwell_about_section_pro"><?php esc_html_e('Free vs PRO', 'pathwell'); ?></a></li>
					<?php } ?>
				</ul>
				<div id="pathwell_about_section_start" class="pathwell_tabs_section pathwell_about_section"><?php
				
					// Install required plugins
					if (!PATHWELL_THEME_FREE_WP && !pathwell_exists_trx_addons()) {
						?><div class="pathwell_about_block"><div class="pathwell_about_block_inner">
							<h2 class="pathwell_about_block_title">
								<i class="dashicons dashicons-admin-plugins"></i>
								<?php esc_html_e('ThemeREX Addons', 'pathwell'); ?>
							</h2>
							<div class="pathwell_about_block_description"><?php
								esc_html_e('It is highly recommended that you install the companion plugin "ThemeREX Addons" to have access to the layouts builder, awesome shortcodes, team and testimonials, services and slider, and many other features ...', 'pathwell');
							?></div>
							<?php pathwell_plugins_installer_get_button_html('trx_addons'); ?>
						</div></div><?php
					}
					
					// Install recommended plugins
					?><div class="pathwell_about_block"><div class="pathwell_about_block_inner">
						<h2 class="pathwell_about_block_title">
							<i class="dashicons dashicons-admin-plugins"></i>
							<?php esc_html_e('Recommended plugins', 'pathwell'); ?>
						</h2>
						<div class="pathwell_about_block_description"><?php
							// Translators: Add the theme name to the message
							echo esc_html(sprintf(__('Theme %s is compatible with a large number of popular plugins. You can install only those that are going to use in the near future.', 'pathwell'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(admin_url().'themes.php?page=tgmpa-install-plugins'); ?>"
						   class="pathwell_about_block_link button button-primary"><?php
							esc_html_e('Install plugins', 'pathwell');
						?></a>
					</div></div><?php
					
					// Customizer or Theme Options
					?><div class="pathwell_about_block"><div class="pathwell_about_block_inner">
						<h2 class="pathwell_about_block_title">
							<i class="dashicons dashicons-admin-appearance"></i>
							<?php esc_html_e('Setup Theme options', 'pathwell'); ?>
						</h2>
						<div class="pathwell_about_block_description"><?php
							esc_html_e('Using the WordPress Customizer you can easily customize every aspect of the theme. If you want to use the standard theme settings page - open Theme Options and follow the same steps there.', 'pathwell');
						?></div>
						<a href="<?php echo esc_url(admin_url().'customize.php'); ?>"
						   class="pathwell_about_block_link button button-primary"><?php
							esc_html_e('Customizer', 'pathwell');
						?></a>
						<?php if (!PATHWELL_THEME_FREE) { ?>
							<?php esc_html_e('or', 'pathwell'); ?>
							<a href="<?php echo esc_url(admin_url().'themes.php?page=theme_options'); ?>"
							   class="pathwell_about_block_link button"><?php
								esc_html_e('Theme Options', 'pathwell');
							?></a>
						<?php } ?>
					</div></div><?php
					
					// Documentation
					?><div class="pathwell_about_block"><div class="pathwell_about_block_inner">
						<h2 class="pathwell_about_block_title">
							<i class="dashicons dashicons-book"></i>
							<?php esc_html_e('Read full documentation', 'pathwell');	?>
						</h2>
						<div class="pathwell_about_block_description"><?php
							// Translators: Add the theme name to the message
							echo esc_html(sprintf(__('Need more details? Please check our full online documentation for detailed information on how to use %s.', 'pathwell'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(pathwell_storage_get('theme_doc_url')); ?>"
						   target="_blank"
						   class="pathwell_about_block_link button button-primary"><?php
							esc_html_e('Documentation', 'pathwell');
						?></a>
					</div></div><?php
					
					// Video tutorials
					?><div class="pathwell_about_block"><div class="pathwell_about_block_inner">
						<h2 class="pathwell_about_block_title">
							<i class="dashicons dashicons-video-alt2"></i>
							<?php esc_html_e('Video tutorials', 'pathwell');	?>
						</h2>
						<div class="pathwell_about_block_description"><?php
							// Translators: Add the theme name to the message
							echo esc_html(sprintf(__('No time for reading documentation? Check out our video tutorials and learn how to customize %s in detail.', 'pathwell'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(pathwell_storage_get('theme_video_url')); ?>"
						   target="_blank"
						   class="pathwell_about_block_link button button-primary"><?php
							esc_html_e('Watch videos', 'pathwell');
						?></a>
					</div></div><?php
					
					// Support
					if (!PATHWELL_THEME_FREE) {
						?><div class="pathwell_about_block"><div class="pathwell_about_block_inner">
							<h2 class="pathwell_about_block_title">
								<i class="dashicons dashicons-sos"></i>
								<?php esc_html_e('Support', 'pathwell'); ?>
							</h2>
							<div class="pathwell_about_block_description"><?php
								// Translators: Add the theme name to the message
								echo esc_html(sprintf(__('We want to make sure you have the best experience using %s and that is why we gathered here all the necessary informations for you.', 'pathwell'), $theme->name));
							?></div>
							<a href="<?php echo esc_url(pathwell_storage_get('theme_support_url')); ?>"
							   target="_blank"
							   class="pathwell_about_block_link button button-primary"><?php
								esc_html_e('Support', 'pathwell');
							?></a>
						</div></div><?php
					}
					
					// Online Demo
					?><div class="pathwell_about_block"><div class="pathwell_about_block_inner">
						<h2 class="pathwell_about_block_title">
							<i class="dashicons dashicons-images-alt2"></i>
							<?php esc_html_e('On-line demo', 'pathwell'); ?>
						</h2>
						<div class="pathwell_about_block_description"><?php
							// Translators: Add the theme name to the message
							echo esc_html(sprintf(__('Visit the Demo Version of %s to check out all the features it has', 'pathwell'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(pathwell_storage_get('theme_demo_url')); ?>"
						   target="_blank"
						   class="pathwell_about_block_link button button-primary"><?php
							esc_html_e('View demo', 'pathwell');
						?></a>
					</div></div>
					
				</div>



				<div id="pathwell_about_section_actions" class="pathwell_tabs_section pathwell_about_section"><?php
				
					// Install required plugins
					if (!PATHWELL_THEME_FREE_WP && !pathwell_exists_trx_addons()) {
						?><div class="pathwell_about_block"><div class="pathwell_about_block_inner">
							<h2 class="pathwell_about_block_title">
								<i class="dashicons dashicons-admin-plugins"></i>
								<?php esc_html_e('ThemeREX Addons', 'pathwell'); ?>
							</h2>
							<div class="pathwell_about_block_description"><?php
								esc_html_e('It is highly recommended that you install the companion plugin "ThemeREX Addons" to have access to the layouts builder, awesome shortcodes, team and testimonials, services and slider, and many other features ...', 'pathwell');
							?></div>
							<?php pathwell_plugins_installer_get_button_html('trx_addons'); ?>
						</div></div><?php
					}
					
					// Install recommended plugins
					?><div class="pathwell_about_block"><div class="pathwell_about_block_inner">
						<h2 class="pathwell_about_block_title">
							<i class="dashicons dashicons-admin-plugins"></i>
							<?php esc_html_e('Recommended plugins', 'pathwell'); ?>
						</h2>
						<div class="pathwell_about_block_description"><?php
							// Translators: Add the theme name to the message
							echo esc_html(sprintf(__('Theme %s is compatible with a large number of popular plugins. You can install only those that are going to use in the near future.', 'pathwell'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(admin_url().'themes.php?page=tgmpa-install-plugins'); ?>"
						   class="pathwell_about_block_link button button button-primary"><?php
							esc_html_e('Install plugins', 'pathwell');
						?></a>
					</div></div><?php
					
					// Customizer or Theme Options
					?><div class="pathwell_about_block"><div class="pathwell_about_block_inner">
						<h2 class="pathwell_about_block_title">
							<i class="dashicons dashicons-admin-appearance"></i>
							<?php esc_html_e('Setup Theme options', 'pathwell'); ?>
						</h2>
						<div class="pathwell_about_block_description"><?php
							esc_html_e('Using the WordPress Customizer you can easily customize every aspect of the theme. If you want to use the standard theme settings page - open Theme Options and follow the same steps there.', 'pathwell');
						?></div>
						<a href="<?php echo esc_url(admin_url().'customize.php'); ?>"
						   target="_blank"
						   class="pathwell_about_block_link button button-primary"><?php
							esc_html_e('Customizer', 'pathwell');
						?></a>
						<?php esc_html_e('or', 'pathwell'); ?>
						<a href="<?php echo esc_url(admin_url().'themes.php?page=theme_options'); ?>"
						   class="pathwell_about_block_link button"><?php
							esc_html_e('Theme Options', 'pathwell');
						?></a>
					</div></div>
					
				</div>



				<?php if (PATHWELL_THEME_FREE) { ?>
					<div id="pathwell_about_section_pro" class="pathwell_tabs_section pathwell_about_section">
						<table class="pathwell_about_table" cellpadding="0" cellspacing="0" border="0">
							<thead>
								<tr>
									<td class="pathwell_about_table_info">&nbsp;</td>
									<td class="pathwell_about_table_check"><?php
										// Translators: Show theme name with suffix 'Free'
										echo esc_html(sprintf(__('%s Free', 'pathwell'), $theme->name));
									?></td>
									<td class="pathwell_about_table_check"><?php
										// Translators: Show theme name with suffix 'PRO'
										echo esc_html(sprintf(__('%s PRO', 'pathwell'), $theme->name));
									?></td>
								</tr>
							</thead>
							<tbody>
	
	
								<?php
								// Responsive layouts
								?>
								<tr>
									<td class="pathwell_about_table_info">
										<h2 class="pathwell_about_table_info_title">
											<?php esc_html_e('Mobile friendly', 'pathwell'); ?>
										</h2>
										<div class="pathwell_about_table_info_description"><?php
											esc_html_e('Responsive layout. Looks great on any device.', 'pathwell');
										?></div>
									</td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-yes"></i></td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
	
								<?php
								// Built-in slider
								?>
								<tr>
									<td class="pathwell_about_table_info">
										<h2 class="pathwell_about_table_info_title">
											<?php esc_html_e('Built-in posts slider', 'pathwell'); ?>
										</h2>
										<div class="pathwell_about_table_info_description"><?php
											esc_html_e('Allows you to add beautiful slides using the built-in shortcode/widget "Slider" with swipe gestures support.', 'pathwell');
										?></div>
									</td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-yes"></i></td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
	
								<?php
								// Revolution slider
								if (pathwell_storage_isset('required_plugins', 'revslider')) {
								?>
								<tr>
									<td class="pathwell_about_table_info">
										<h2 class="pathwell_about_table_info_title">
											<?php esc_html_e('Revolution Slider Compatibility', 'pathwell'); ?>
										</h2>
										<div class="pathwell_about_table_info_description"><?php
											esc_html_e('Our built-in shortcode/widget "Slider" is able to work not only with posts, but also with slides created  in "Revolution Slider".', 'pathwell');
										?></div>
									</td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-yes"></i></td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
								<?php } ?>
	
								<?php
								// SiteOrigin Panels
								if (pathwell_storage_isset('required_plugins', 'siteorigin-panels')) {
								?>
								<tr>
									<td class="pathwell_about_table_info">
										<h2 class="pathwell_about_table_info_title">
											<?php esc_html_e('Free PageBuilder', 'pathwell'); ?>
										</h2>
										<div class="pathwell_about_table_info_description"><?php
											esc_html_e('Full integration with a nice free page builder "SiteOrigin Panels".', 'pathwell');
										?></div>
									</td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-yes"></i></td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
								<tr>
									<td class="pathwell_about_table_info">
										<h2 class="pathwell_about_table_info_title">
											<?php esc_html_e('Additional widgets pack', 'pathwell'); ?>
										</h2>
										<div class="pathwell_about_table_info_description"><?php
											esc_html_e('A number of useful widgets to create beautiful homepages and other sections of your website with SiteOrigin Panels.', 'pathwell');
										?></div>
									</td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-no"></i></td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
								<?php } ?>
	
								<?php
								// Visual Composer
								?>
								<tr>
									<td class="pathwell_about_table_info">
										<h2 class="pathwell_about_table_info_title">
											<?php esc_html_e('Visual Composer', 'pathwell'); ?>
										</h2>
										<div class="pathwell_about_table_info_description"><?php
											esc_html_e('Full integration with a very popular page builder "Visual Composer". A number of useful shortcodes and widgets to create beautiful homepages and other sections of your website.', 'pathwell');
										?></div>
									</td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-no"></i></td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
								<tr>
									<td class="pathwell_about_table_info">
										<h2 class="pathwell_about_table_info_title">
											<?php esc_html_e('Additional shortcodes pack', 'pathwell'); ?>
										</h2>
										<div class="pathwell_about_table_info_description"><?php
											esc_html_e('A number of useful shortcodes to create beautiful homepages and other sections of your website with Visual Composer.', 'pathwell');
										?></div>
									</td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-no"></i></td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
	
								<?php
								// Layouts builder
								?>
								<tr>
									<td class="pathwell_about_table_info">
										<h2 class="pathwell_about_table_info_title">
											<?php esc_html_e('Headers and Footers builder', 'pathwell'); ?>
										</h2>
										<div class="pathwell_about_table_info_description"><?php
											esc_html_e('Powerful visual builder of headers and footers! No manual code editing - use all the advantages of drag-and-drop technology.', 'pathwell');
										?></div>
									</td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-no"></i></td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
	
								<?php
								// WooCommerce
								if (pathwell_storage_isset('required_plugins', 'woocommerce')) {
								?>
								<tr>
									<td class="pathwell_about_table_info">
										<h2 class="pathwell_about_table_info_title">
											<?php esc_html_e('WooCommerce Compatibility', 'pathwell'); ?>
										</h2>
										<div class="pathwell_about_table_info_description"><?php
											esc_html_e('Ready for e-commerce. You can build an online store with this theme.', 'pathwell');
										?></div>
									</td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-yes"></i></td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
								<?php } ?>
	
								<?php
								// Easy Digital Downloads
								if (pathwell_storage_isset('required_plugins', 'easy-digital-downloads')) {
								?>
								<tr>
									<td class="pathwell_about_table_info">
										<h2 class="pathwell_about_table_info_title">
											<?php esc_html_e('Easy Digital Downloads Compatibility', 'pathwell'); ?>
										</h2>
										<div class="pathwell_about_table_info_description"><?php
											esc_html_e('Ready for digital e-commerce. You can build an online digital store with this theme.', 'pathwell');
										?></div>
									</td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-no"></i></td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
								<?php } ?>
	
								<?php
								// Other plugins
								?>
								<tr>
									<td class="pathwell_about_table_info">
										<h2 class="pathwell_about_table_info_title">
											<?php esc_html_e('Many other popular plugins compatibility', 'pathwell'); ?>
										</h2>
										<div class="pathwell_about_table_info_description"><?php
											esc_html_e('PRO version is compatible (was tested and has built-in support) with many popular plugins.', 'pathwell');
										?></div>
									</td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-no"></i></td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
	
								<?php
								// Support
								?>
								<tr>
									<td class="pathwell_about_table_info">
										<h2 class="pathwell_about_table_info_title">
											<?php esc_html_e('Support', 'pathwell'); ?>
										</h2>
										<div class="pathwell_about_table_info_description"><?php
											esc_html_e('Our premium support is going to take care of any problems, in case there will be any of course.', 'pathwell');
										?></div>
									</td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-no"></i></td>
									<td class="pathwell_about_table_check"><i class="dashicons dashicons-yes"></i></td>
								</tr>
	
								<?php
								// Get PRO version
								?>
								<tr>
									<td class="pathwell_about_table_info">&nbsp;</td>
									<td class="pathwell_about_table_check" colspan="2">
										<a href="<?php echo esc_url(pathwell_storage_get('theme_download_url')); ?>"
										   target="_blank"
										   class="pathwell_about_block_link pathwell_about_pro_link button button-primary"><?php
											esc_html_e('Get PRO version', 'pathwell');
										?></a>
									</td>
								</tr>
	
							</tbody>
						</table>
					</div>
				<?php } ?>
				
			</div>
		</div>
		<?php
	}
}


// Utils
//------------------------------------

// Return supported plugin's names
if (!function_exists('pathwell_about_get_supported_plugins')) {
	function pathwell_about_get_supported_plugins() {
		return '"' . join('", "', array_values(pathwell_storage_get('required_plugins'))) . '"';
	}
}

require_once PATHWELL_THEME_DIR . 'includes/plugins-installer/plugins-installer.php';
?>