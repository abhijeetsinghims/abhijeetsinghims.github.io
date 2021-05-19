<?php
/**
 * Theme Options, Color Schemes and Fonts utilities
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

// -----------------------------------------------------------------
// -- Create and manage Theme Options
// -----------------------------------------------------------------

// Theme init priorities:
// 2 - create Theme Options
if (!function_exists('pathwell_options_theme_setup2')) {
	add_action( 'after_setup_theme', 'pathwell_options_theme_setup2', 2 );
	function pathwell_options_theme_setup2() {
		pathwell_create_theme_options();
	}
}

// Step 1: Load default settings and previously saved mods
if (!function_exists('pathwell_options_theme_setup5')) {
	add_action( 'after_setup_theme', 'pathwell_options_theme_setup5', 5 );
	function pathwell_options_theme_setup5() {
		pathwell_storage_set('options_reloaded', false);
		pathwell_load_theme_options();
	}
}

// Step 2: Load current theme customization mods
if (is_customize_preview()) {
	if (!function_exists('pathwell_load_custom_options')) {
		add_action( 'wp_loaded', 'pathwell_load_custom_options' );
		function pathwell_load_custom_options() {
			if (!pathwell_storage_get('options_reloaded')) {
				pathwell_storage_set('options_reloaded', true);
				pathwell_load_theme_options();
			}
		}
	}
}

// Load current values for each customizable option
if ( !function_exists('pathwell_load_theme_options') ) {
	function pathwell_load_theme_options() {
		$options = pathwell_storage_get('options');
		$reset = (int) get_theme_mod('reset_options', 0);
		foreach ($options as $k=>$v) {
			if (isset($v['std'])) {
				$value = pathwell_get_theme_option_std($k, $v['std']);
				if (!$reset) {
					if (isset($_GET[$k]))
						$value = wp_kses_data(wp_unslash($_GET[$k]));
					else {
						$default_value = -987654321;
						$tmp = get_theme_mod($k, $default_value);
						if ($tmp != $default_value) $value = $tmp;
					}
				}
				pathwell_storage_set_array2('options', $k, 'val', $value);
				if ($reset) remove_theme_mod($k);
			}
		}
		if ($reset) {
			// Unset reset flag
			set_theme_mod('reset_options', 0);
			// Regenerate CSS with default colors and fonts
			pathwell_customizer_save_css();
		} else {
			do_action('pathwell_action_load_options');
		}
	}
}

// Override options with stored page/post meta
if ( !function_exists('pathwell_override_theme_options') ) {
	add_action( 'wp', 'pathwell_override_theme_options', 1 );
	function pathwell_override_theme_options($query=null) {
		if (is_page_template('blog.php')) {
			pathwell_storage_set('blog_archive', true);
			pathwell_storage_set('blog_template', get_the_ID());
		}
		pathwell_storage_set('blog_mode', pathwell_detect_blog_mode());
		if (is_singular()) {
			pathwell_storage_set('options_meta', get_post_meta(get_the_ID(), 'pathwell_options', true));
		}
		do_action('pathwell_action_override_theme_options');
	}
}

// Override options with stored page meta on 'Blog posts' pages
if ( !function_exists('pathwell_blog_override_theme_options') ) {
	add_action( 'pathwell_action_override_theme_options', 'pathwell_blog_override_theme_options');
	function pathwell_blog_override_theme_options() {
		global $wp_query;
		if (is_home() && !is_front_page() && !empty($wp_query->is_posts_page)) {
			if (($id = get_option('page_for_posts')) > 0)
				pathwell_storage_set('options_meta', get_post_meta($id, 'pathwell_options', true));
		}
	}
}


// Return 'std' value of the option, processed by special function (if specified)
if (!function_exists('pathwell_get_theme_option_std')) {
	function pathwell_get_theme_option_std($opt_name, $opt_std) {
		if (strpos($opt_std, '$pathwell_')!==false) {
			$func = substr($opt_std, 1);
			if (function_exists($func)) {
				$opt_std = $func($opt_name);
			}
		}
		return $opt_std;
	}
}


// Return customizable option value
if (!function_exists('pathwell_get_theme_option')) {
	function pathwell_get_theme_option($name, $defa='', $strict_mode=false, $post_id=0) {
		$rez = $defa;
		$from_post_meta = false;

		if ($post_id > 0) {
			if (!pathwell_storage_isset('post_options_meta', $post_id))
				pathwell_storage_set_array('post_options_meta', $post_id, get_post_meta($post_id, 'pathwell_options', true));
			if (pathwell_storage_isset('post_options_meta', $post_id, $name)) {
				$tmp = pathwell_storage_get_array('post_options_meta', $post_id, $name);
				if (!pathwell_is_inherit($tmp)) {
					$rez = $tmp;
					$from_post_meta = true;
				}
			}
		}

		if (!$from_post_meta && pathwell_storage_isset('options')) {

			$blog_mode = pathwell_storage_get('blog_mode');

			if ( !pathwell_storage_isset('options', $name) && (empty($blog_mode) || !pathwell_storage_isset('options', $name.'_'.$blog_mode)) ) {
				$rez = $tmp = '_not_exists_';
				if (function_exists('trx_addons_get_option'))
					$rez = trx_addons_get_option($name, $tmp, false);
				if ($rez === $tmp) {
					if ($strict_mode) {
						$s = debug_backtrace();
						//array_shift($s);
						$s = array_shift($s);
						// Translators: Add option's name to the output
						echo '<pre>' . esc_html(sprintf(__('Undefined option "%s" called from:', 'pathwell'), $name));
						if (function_exists('dco')) dco($s);
						else print_r($s);
						echo '</pre>';
						die();
					} else
						$rez = $defa;
				}

			} else {

				$blog_mode_parent = $blog_mode=='post'
										? 'blog'
										: str_replace('_single', '', $blog_mode);

				// Override option from GET or POST for current blog mode
				if (!empty($blog_mode) && isset($_REQUEST[$name . '_' . $blog_mode])) {
					$rez = wp_kses_data(wp_unslash($_REQUEST[$name . '_' . $blog_mode]));

				// Override option from GET
				} else if (isset($_REQUEST[$name])) {
					$rez = wp_kses_data(wp_unslash($_REQUEST[$name]));

				// Override option from current page settings (if exists)
				} else if (pathwell_storage_isset('options_meta', $name) && !pathwell_is_inherit(pathwell_storage_get_array('options_meta', $name))) {
					$rez = pathwell_storage_get_array('options_meta', $name);

				// Override option from current blog mode settings: 'front', 'search', 'page', 'post', 'blog', etc. (if exists)
				} else if (!empty($blog_mode) && pathwell_storage_isset('options', $name . '_' . $blog_mode, 'val') && !pathwell_is_inherit(pathwell_storage_get_array('options', $name . '_' . $blog_mode, 'val'))) {
					$rez = pathwell_storage_get_array('options', $name . '_' . $blog_mode, 'val');

				// Override option for 'post' from 'blog' settings (if exists)
				// Also used for override 'xxx_single' on the 'xxx'
				// (for example, instead 'sidebar_courses_single' return option for 'sidebar_courses')
				} else if (!empty($blog_mode_parent) && $blog_mode!=$blog_mode_parent && pathwell_storage_isset('options', $name . '_' . $blog_mode_parent, 'val') && !pathwell_is_inherit(pathwell_storage_get_array('options', $name . '_' . $blog_mode_parent, 'val'))) {
					$rez = pathwell_storage_get_array('options', $name . '_' . $blog_mode_parent, 'val');

				// Get saved option value
				} else if (pathwell_storage_isset('options', $name, 'val')) {
					$rez = pathwell_storage_get_array('options', $name, 'val');

				// Get ThemeREX Addons option value
				} else if (function_exists('trx_addons_get_option')) {
					$rez = trx_addons_get_option($name, $defa, false);

				}
			}
		}
		return $rez;
	}
}


// Check if customizable option exists
if (!function_exists('pathwell_check_theme_option')) {
	function pathwell_check_theme_option($name) {
		return pathwell_storage_isset('options', $name);
	}
}


// Return customizable option value, stored in the posts meta
if (!function_exists('pathwell_get_theme_option_from_meta')) {
	function pathwell_get_theme_option_from_meta($name, $defa='') {
		$rez = $defa;
		if (pathwell_storage_isset('options_meta')) {
			if (pathwell_storage_isset('options_meta', $name))
				$rez = pathwell_storage_get_array('options_meta', $name);
			else
				$rez = 'inherit';
		}
		return $rez;
	}
}


// Get dependencies list from the Theme Options
if ( !function_exists('pathwell_get_theme_dependencies') ) {
	function pathwell_get_theme_dependencies() {
		$options = pathwell_storage_get('options');
		$depends = array();
		foreach ($options as $k=>$v) {
			if (isset($v['dependency'])) 
				$depends[$k] = $v['dependency'];
		}
		return $depends;
	}
}



// -----------------------------------------------------------------
// -- Theme Settings utilities
// -----------------------------------------------------------------

// Return internal theme setting value
if (!function_exists('pathwell_get_theme_setting')) {
	function pathwell_get_theme_setting($name) {
		if ( !pathwell_storage_isset('settings', $name) ) {
			$s = debug_backtrace();
			//array_shift($s);
			$s = array_shift($s);
			// Translators: Add setting's name to the output
			echo '<pre>' . esc_html(sprintf(__('Undefined setting "%s" called from:', 'pathwell'), $name));
			if (function_exists('dco')) dco($s);
			else print_r($s);
			echo '</pre>';
			die();
		} else
			return pathwell_storage_get_array('settings', $name);
	}
}

// Set theme setting
if ( !function_exists( 'pathwell_set_theme_setting' ) ) {
	function pathwell_set_theme_setting($option_name, $value) {
		if (pathwell_storage_isset('settings', $option_name))
			pathwell_storage_set_array('settings', $option_name, $value);
	}
}



// -----------------------------------------------------------------
// -- Color Schemes utilities
// -----------------------------------------------------------------

// Load saved values to the color schemes
if (!function_exists('pathwell_load_schemes')) {
	add_action('pathwell_action_load_options', 'pathwell_load_schemes');
	function pathwell_load_schemes() {
		$schemes = pathwell_storage_get('schemes');
		$storage = pathwell_unserialize(pathwell_get_theme_option('scheme_storage'));
		if (is_array($storage) && count($storage) > 0)  {
			foreach ($storage as $k=>$v) {
				if (isset($schemes[$k])) {
					$schemes[$k] = $v;
				}
			}
			pathwell_storage_set('schemes', $schemes);
		}
	}
}

// Return specified color from current (or specified) color scheme
if ( !function_exists( 'pathwell_get_scheme_color' ) ) {
	function pathwell_get_scheme_color($color_name, $scheme = '') {
		if (empty($scheme)) $scheme = pathwell_get_theme_option( 'color_scheme' );
		if (empty($scheme) || pathwell_storage_empty('schemes', $scheme)) $scheme = 'default';
		$colors = pathwell_storage_get_array('schemes', $scheme, 'colors');
		return $colors[$color_name];
	}
}

// Return colors from current color scheme
if ( !function_exists( 'pathwell_get_scheme_colors' ) ) {
	function pathwell_get_scheme_colors($scheme = '') {
		if (empty($scheme)) $scheme = pathwell_get_theme_option( 'color_scheme' );
		if (empty($scheme) || pathwell_storage_empty('schemes', $scheme)) $scheme = 'default';
		return pathwell_storage_get_array('schemes', $scheme, 'colors');
	}
}

// Return colors from all schemes
if ( !function_exists( 'pathwell_get_scheme_storage' ) ) {
	function pathwell_get_scheme_storage($scheme = '') {
		return serialize(pathwell_storage_get('schemes'));
	}
}

// Return schemes list
if ( !function_exists( 'pathwell_get_list_schemes' ) ) {
	function pathwell_get_list_schemes($prepend_inherit=false) {
		$list = array();
		$schemes = pathwell_storage_get('schemes');
		if (is_array($schemes) && count($schemes) > 0) {
			foreach ($schemes as $slug => $scheme) {
				$list[$slug] = $scheme['title'];
			}
		}
		return $prepend_inherit ? pathwell_array_merge(array('inherit' => esc_html__("Inherit", 'pathwell')), $list) : $list;
	}
}



// -----------------------------------------------------------------
// -- Theme Fonts utilities
// -----------------------------------------------------------------

// Load saved values into fonts list
if (!function_exists('pathwell_load_fonts')) {
	add_action('pathwell_action_load_options', 'pathwell_load_fonts');
	function pathwell_load_fonts() {
		// Fonts to load when theme starts
		$load_fonts = array();
		for ($i=1; $i<=pathwell_get_theme_setting('max_load_fonts'); $i++) {
			if (($name = pathwell_get_theme_option("load_fonts-{$i}-name")) != '') {
				$load_fonts[] = array(
					'name'	 => $name,
					'family' => pathwell_get_theme_option("load_fonts-{$i}-family"),
					'styles' => pathwell_get_theme_option("load_fonts-{$i}-styles")
				);
			}
		}
		pathwell_storage_set('load_fonts', $load_fonts);
		pathwell_storage_set('load_fonts_subset', pathwell_get_theme_option("load_fonts_subset"));
		
		// Font parameters of the main theme's elements
		$fonts = pathwell_get_theme_fonts();
		foreach ($fonts as $tag=>$v) {
			foreach ($v as $css_prop=>$css_value) {
				if (in_array($css_prop, array('title', 'description'))) continue;
				$fonts[$tag][$css_prop] = pathwell_get_theme_option("{$tag}_{$css_prop}");
			}
		}
		pathwell_storage_set('theme_fonts', $fonts);
	}
}

// Return slug of the loaded font
if (!function_exists('pathwell_get_load_fonts_slug')) {
	function pathwell_get_load_fonts_slug($name) {
		return str_replace(' ', '-', $name);
	}
}

// Return load fonts parameter's default value
if (!function_exists('pathwell_get_load_fonts_option')) {
	function pathwell_get_load_fonts_option($option_name) {
		$rez = '';
		$parts = explode('-', $option_name);
		$load_fonts = pathwell_storage_get('load_fonts');
		if ($parts[0] == 'load_fonts' && count($load_fonts) > $parts[1]-1 && isset($load_fonts[$parts[1]-1][$parts[2]])) {
			$rez = $load_fonts[$parts[1]-1][$parts[2]];
		}
		return $rez;
	}
}

// Return load fonts subset's default value
if (!function_exists('pathwell_get_load_fonts_subset')) {
	function pathwell_get_load_fonts_subset($option_name) {
		return pathwell_storage_get('load_fonts_subset');
	}
}

// Return load fonts list
if (!function_exists('pathwell_get_list_load_fonts')) {
	function pathwell_get_list_load_fonts($prepend_inherit=false) {
		$list = array();
		$load_fonts = pathwell_storage_get('load_fonts');
		if (is_array($load_fonts) && count($load_fonts) > 0) {
			foreach ($load_fonts as $font) {
				$list['"'.trim($font['name']).'"'.(!empty($font['family']) ? ','.trim($font['family']): '')] = $font['name'];
			}
		}
		return $prepend_inherit ? pathwell_array_merge(array('inherit' => esc_html__("Inherit", 'pathwell')), $list) : $list;
	}
}

// Return font settings of the theme specific elements
if ( !function_exists( 'pathwell_get_theme_fonts' ) ) {
	function pathwell_get_theme_fonts() {
		return pathwell_storage_get('theme_fonts');
	}
}

// Return theme fonts parameter's default value
if (!function_exists('pathwell_get_theme_fonts_option')) {
	function pathwell_get_theme_fonts_option($option_name) {
		$rez = '';
		$parts = explode('_', $option_name);
		$theme_fonts = pathwell_storage_get('theme_fonts');
		if (!empty($theme_fonts[$parts[0]][$parts[1]])) {
			$rez = $theme_fonts[$parts[0]][$parts[1]];
		}
		return $rez;
	}
}

// Update loaded fonts list in the each tag's parameter (p, h1..h6,...) after the 'load_fonts' options are loaded
if (!function_exists('pathwell_update_list_load_fonts')) {
	add_action('pathwell_action_load_options', 'pathwell_update_list_load_fonts', 11);
	function pathwell_update_list_load_fonts() {
		$theme_fonts = pathwell_get_theme_fonts();
		$load_fonts = pathwell_get_list_load_fonts(true);
		foreach ($theme_fonts as $tag=>$v) {
			pathwell_storage_set_array2('options', $tag.'_font-family', 'options', $load_fonts);
		}
	}
}



// -----------------------------------------------------------------
// -- Other options utilities
// -----------------------------------------------------------------

// Return current theme-specific border radius for form's fields and buttons
if ( !function_exists( 'pathwell_get_border_radius' ) ) {
	function pathwell_get_border_radius() {
		$rad = str_replace(' ', '', pathwell_get_theme_option('border_radius'));
		if (empty($rad)) $rad = 0;
		return pathwell_prepare_css_value($rad); 
	}
}




// -----------------------------------------------------------------
// -- Theme Options page
// -----------------------------------------------------------------

if ( !function_exists('pathwell_options_init_page_builder') ) {
	add_action( 'after_setup_theme', 'pathwell_options_init_page_builder' );
	function pathwell_options_init_page_builder() {
		if ( is_admin() ) {
			add_action('admin_enqueue_scripts',	'pathwell_options_add_scripts');
		}
	}
}
	
// Load required styles and scripts for admin mode
if ( !function_exists( 'pathwell_options_add_scripts' ) ) {
	//Handler of the add_action("admin_enqueue_scripts", 'pathwell_options_add_scripts');
	function pathwell_options_add_scripts() {
		$screen = function_exists('get_current_screen') ? get_current_screen() : false;
		if (is_object($screen) && $screen->id == 'appearance_page_theme_options') {
			wp_enqueue_style( 'pathwell-icons',  pathwell_get_file_url('css/font-icons/css/fontello-embedded.css'), array(), null );
			wp_enqueue_style( 'wp-color-picker', false, array(), null);
			wp_enqueue_script('wp-color-picker', false, array('jquery'), null, true);
			wp_enqueue_script( 'jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true );
			wp_enqueue_script( 'jquery-ui-accordion', false, array('jquery', 'jquery-ui-core'), null, true );
			wp_enqueue_script( 'pathwell-options', pathwell_get_file_url('theme-options/theme-options.js'), array('jquery'), null, true );
			wp_enqueue_script( 'pathwell-colorpicker.colors', pathwell_get_file_url('js/colorpicker/colors.js'), array('jquery'), null, true );
			wp_enqueue_script( 'pathwell-colorpicker', pathwell_get_file_url('js/colorpicker/jqColorPicker.js'), array('jquery'), null, true );
			wp_localize_script( 'pathwell-options', 'pathwell_dependencies', pathwell_get_theme_dependencies() );
			wp_localize_script( 'pathwell-options', 'pathwell_color_schemes', pathwell_storage_get('schemes') );
			wp_localize_script( 'pathwell-options', 'pathwell_simple_schemes', pathwell_storage_get('schemes_simple') );
		}
	}
}

// Add Theme Options item in the Appearance menu
if (!function_exists('pathwell_options_add_menu_items')) {
	add_action( 'admin_menu', 'pathwell_options_add_menu_items' );
	function pathwell_options_add_menu_items() {
		if (!PATHWELL_THEME_FREE) {
			add_theme_page(
				esc_html__('Theme Options', 'pathwell'),	//page_title
				esc_html__('Theme Options', 'pathwell'),	//menu_title
				'manage_options',						//capability
				'theme_options',						//menu_slug
				'pathwell_options_page_builder',			//callback
				'dashicons-admin-generic',				//icon
				''										//menu position
			);
		}
	}
}


// Build options page
if (!function_exists('pathwell_options_page_builder')) {
	function pathwell_options_page_builder() {
		?>
		<div class="pathwell_options">
			<h2 class="pathwell_options_title"><?php esc_html_e('Theme Options', 'pathwell'); ?></h2>
			<?php pathwell_show_admin_messages(); ?>
			<form id="pathwell_options_form" action="#" method="post" enctype="multipart/form-data">
				<input type="hidden" name="pathwell_nonce" value="<?php echo esc_attr(wp_create_nonce(admin_url())); ?>" />
				<?php pathwell_options_show_fields(); ?>
				<div class="pathwell_options_buttons">
					<input type="submit" value="<?php esc_html_e('Save Options', 'pathwell'); ?>">
				</div>
			</form>
		</div>
		<?php
	}
}


// Display all option's fields
if ( !function_exists('pathwell_options_show_fields') ) {
	function pathwell_options_show_fields($options=false) {
		if (empty($options)) $options = pathwell_storage_get('options');
		$tabs_titles = $tabs_content = array();
		$last_panel = $last_section = $last_group = '';
		foreach ($options as $k=>$v) {
			// New tab
			if ($v['type']=='panel' || ($v['type']=='section' && empty($last_panel))) {
				if (!isset($tabs_titles[$k])) {
					$tabs_titles[$k] = $v['title'];
					$tabs_content[$k] = '';
				}
				if (!empty($last_group)) {
					$tabs_content[$last_section] .= '</div></div>';
					$last_group = '';
				}
				$last_section = $k;
				if ($v['type']=='panel') $last_panel = $k;

			// New group
			} else if ($v['type']=='group' || ($v['type']=='section' && !empty($last_panel))) {
				if (empty($last_group))
					$tabs_content[$last_section] = (!isset($tabs_content[$last_section]) ? '' : $tabs_content[$last_section]) 
													. '<div class="pathwell_accordion pathwell_options_groups">';
				else
					$tabs_content[$last_section] .= '</div>';
				$tabs_content[$last_section] .= '<h4 class="pathwell_accordion_title pathwell_options_group_title">' . esc_html($v['title']) . '</h4>'
												. '<div class="pathwell_accordion_content pathwell_options_group_content">';
				$last_group = $k;
			
			// End panel, section or group
			} else if (in_array($v['type'], array('group_end', 'section_end', 'panel_end'))) {
				if (!empty($last_group) && ($v['type'] != 'section_end' || empty($last_panel))) {
					$tabs_content[$last_section] .= '</div></div>';
					$last_group = '';
				}
				if ($v['type'] == 'panel_end') $last_panel = '';
				
			// Field's layout
			} else {
				$tabs_content[$last_section] = (!isset($tabs_content[$last_section]) ? '' : $tabs_content[$last_section]) 
												. pathwell_options_show_field($k, $v);
			}
		}
		if (!empty($last_group)) {
			$tabs_content[$last_section] .= '</div></div>';
		}
		
		if (count($tabs_content) > 0) {
			// Remove empty sections
			foreach ($tabs_content as $k=>$v) {
				if (empty($v)) {
					unset($tabs_titles[$k]);
					unset($tabs_content[$k]);
				}
			}
			?>
			<div id="pathwell_options_tabs" class="pathwell_tabs <?php echo count($tabs_titles) > 1 ? 'with_tabs' : 'no_tabs'; ?>">
				<?php if (count($tabs_titles) > 1) { ?>
					<ul><?php
						$cnt = 0;
						foreach ($tabs_titles as $k=>$v) {
							$cnt++;
							?><li><a href="#pathwell_options_section_<?php echo esc_attr($cnt); ?>"><?php echo esc_html($v); ?></a></li><?php
						}
					?></ul>
				<?php
				}
				$cnt = 0;
				foreach ($tabs_content as $k=>$v) {
					$cnt++;
					?>
					<div id="pathwell_options_section_<?php echo esc_attr($cnt); ?>" class="pathwell_tabs_section pathwell_options_section">
						<?php pathwell_show_layout($v); ?>
					</div>
					<?php
				}
				?>
			</div>
			<?php
		}
	}
}


// Display single option's field
if ( !function_exists('pathwell_options_show_field') ) {
	function pathwell_options_show_field($name, $field, $post_type='') {

		$inherit_allow = !empty($post_type);
		$inherit_state = !empty($post_type) && isset($field['val']) && pathwell_is_inherit($field['val']);
		
		$field_data_present = $field['type']!='info' || !empty($field['override']['desc']) || !empty($field['desc']);

		if (   ($field['type'] == 'hidden' && $inherit_allow) 	// Hidden field in the post meta (not in the root Theme Options)
			|| (!empty($field['hidden']) && !$inherit_allow)	// Field only for post meta in the root Theme Options
		   ) return '';
		
		if ($field['type'] == 'hidden') {

			$output = isset($field['val'])
							? '<input type="hidden" name="pathwell_options_field_'.esc_attr($name).'"'
								. ' value="'.esc_attr($field['val']).'"'
								. ' />'
							: '';

		} else {
		
		$output = (!empty($field['class']) && strpos($field['class'], 'pathwell_new_row')!==false 
					? '<div class="pathwell_new_row_before"></div>'
					: '')
					. '<div class="pathwell_options_item pathwell_options_item_'.esc_attr($field['type'])
								. ($inherit_allow ? ' pathwell_options_inherit_'.($inherit_state ? 'on' : 'off' ) : '')
								. (!empty($field['class']) ? ' '.esc_attr($field['class']) : '')
								. '">'
						. '<h4 class="pathwell_options_item_title">'
							. esc_html($field['title'])
							. ($inherit_allow 
									? '<span class="pathwell_options_inherit_lock" id="pathwell_options_inherit_'.esc_attr($name).'"></span>'
									: '')
						. '</h4>'
						. ($field_data_present
							? '<div class="pathwell_options_item_data">'
								. '<div class="pathwell_options_item_field" data-param="'.esc_attr($name).'"'
									. (!empty($field['linked']) ? ' data-linked="'.esc_attr($field['linked']).'"' : '')
									. '>'
							: '');
	
		// Type 'checkbox'
		if ($field['type']=='checkbox') {
			$output .= '<label class="pathwell_options_item_label">'
						. '<input type="checkbox" name="pathwell_options_field_'.esc_attr($name).'" value="1"'
								.($field['val']==1 ? ' checked="checked"' : '')
								.' />'
						. esc_html($field['title'])
					. '</label>';
		
		// Type 'switch' (2 choises) or 'radio' (3+ choises)
		} else if (in_array($field['type'], array('switch', 'radio'))) {
			$field['options'] = apply_filters('pathwell_filter_options_get_list_choises', $field['options'], $name);
			$first = true;
			foreach ($field['options'] as $k=>$v) {
				$output .= '<label class="pathwell_options_item_label">'
							. '<input type="radio" name="pathwell_options_field_'.esc_attr($name).'"'
									. ' value="'.esc_attr($k).'"'
									. ($field['val']==$k || ($first && !isset($field['options'][$field['val']])) ? ' checked="checked"' : '')
									. ' />'
							. esc_html($v)
						. '</label>';
				$first = false;
			}

		// Type 'text' or 'time' or 'date'
		} else if (in_array($field['type'], array('text', 'time', 'date'))) {
			$output .= '<input type="text" name="pathwell_options_field_'.esc_attr($name).'"'
							. ' value="'.esc_attr(pathwell_is_inherit($field['val']) ? '' : $field['val']).'"'
							. ' />';
		
		// Type 'textarea'
		} else if ($field['type']=='textarea') {
			$output .= '<textarea name="pathwell_options_field_'.esc_attr($name).'">'
							. esc_html(pathwell_is_inherit($field['val']) ? '' : $field['val'])
						. '</textarea>';
		
		// Type 'text_editor'
		} else if ($field['type']=='text_editor') {
			$output .= '<input type="hidden" id="pathwell_options_field_'.esc_attr($name).'"'
							. ' name="pathwell_options_field_'.esc_attr($name).'"'
							. ' value="'.esc_textarea(pathwell_is_inherit($field['val']) ? '' : $field['val']).'"'
							. ' />'
						. pathwell_show_custom_field('pathwell_options_field_'.esc_attr($name).'_tinymce',
													$field,
													pathwell_is_inherit($field['val']) ? '' : $field['val']);

		// Type 'select'
		} else if ($field['type']=='select') {
			$field['options'] = apply_filters('pathwell_filter_options_get_list_choises', $field['options'], $name);
			$output .= '<select size="1" name="pathwell_options_field_'.esc_attr($name).'">';
			foreach ($field['options'] as $k=>$v) {
				$output .= '<option value="'.esc_attr($k).'"'.($field['val']==$k ? ' selected="selected"' : '').'>'.esc_html($v).'</option>';
			}
			$output .= '</select>';

		// Type 'image', 'media', 'video' or 'audio'
		} else if (in_array($field['type'], array('image', 'media', 'video', 'audio'))) {
			if ( (int) $field['val'] > 0 ) {
				$image = wp_get_attachment_image_src( $field['val'], 'full' );
				$field['val'] = $image[0];
			}
			$output .= (!empty($field['multiple'])
						? '<input type="hidden" id="pathwell_options_field_'.esc_attr($name).'"'
							. ' name="pathwell_options_field_'.esc_attr($name).'"'
							. ' value="'.esc_attr(pathwell_is_inherit($field['val']) ? '' : $field['val']).'"'
							. ' />'
						: '<input type="text" id="pathwell_options_field_'.esc_attr($name).'"'
							. ' name="pathwell_options_field_'.esc_attr($name).'"'
							. ' value="'.esc_attr(pathwell_is_inherit($field['val']) ? '' : $field['val']).'"'
							. ' />')
					. pathwell_show_custom_field('pathwell_options_field_'.esc_attr($name).'_button',
												array(
													'type'			 => 'mediamanager',
													'multiple'		 => !empty($field['multiple']),
													'data_type'		 => $field['type'],
													'linked_field_id'=> 'pathwell_options_field_'.esc_attr($name)
												),
												pathwell_is_inherit($field['val']) ? '' : $field['val']);

		// Type 'color'
		} else if ($field['type']=='color') {
			$output .= '<input type="text" id="pathwell_options_field_'.esc_attr($name).'"'
							. ' class="pathwell_color_selector"'
							. ' name="pathwell_options_field_'.esc_attr($name).'"'
							. ' value="'.esc_attr($field['val']).'"'
							. ' />';
		
		// Type 'icon'
		} else if ($field['type']=='icon') {
			$output .= '<input type="text" id="pathwell_options_field_'.esc_attr($name).'"'
							. ' name="pathwell_options_field_'.esc_attr($name).'"'
							. ' value="'.esc_attr(pathwell_is_inherit($field['val']) ? '' : $field['val']).'"'
							. ' />'
						. pathwell_show_custom_field('pathwell_options_field_'.esc_attr($name).'_button',
													array(
														'type'	 => 'icons',
														'button' => true,
														'icons'	 => true
													),
													pathwell_is_inherit($field['val']) ? '' : $field['val']);
		
		// Type 'checklist'
		} else if ($field['type']=='checklist') {
			$output .= '<input type="hidden" id="pathwell_options_field_'.esc_attr($name).'"'
							. ' name="pathwell_options_field_'.esc_attr($name).'"'
							. ' value="'.esc_attr(pathwell_is_inherit($field['val']) ? '' : $field['val']).'"'
							. ' />'
						. pathwell_show_custom_field('pathwell_options_field_'.esc_attr($name).'_list',
													$field,
													pathwell_is_inherit($field['val']) ? '' : $field['val']);
		
		// Type 'scheme_editor'
		} else if ($field['type']=='scheme_editor') {
			$output .= '<input type="hidden" id="pathwell_options_field_'.esc_attr($name).'"'
							. ' name="pathwell_options_field_'.esc_attr($name).'"'
							. ' value="'.esc_attr(pathwell_is_inherit($field['val']) ? '' : $field['val']).'"'
							. ' />'
						. pathwell_show_custom_field('pathwell_options_field_'.esc_attr($name).'_scheme',
													$field,
													pathwell_unserialize($field['val']));
		
		// Type 'slider' || 'range'
		} else if (in_array($field['type'], array('slider', 'range'))) {
			$field['show_value'] = !isset($field['show_value']) || $field['show_value'];
			$output .= '<input type="'.(!$field['show_value'] ? 'hidden' : 'text').'" id="pathwell_options_field_'.esc_attr($name).'"'
							. ' name="pathwell_options_field_'.esc_attr($name).'"'
							. ' value="'.esc_attr(pathwell_is_inherit($field['val']) ? '' : $field['val']).'"'
							. ($field['show_value'] ? ' class="pathwell_range_slider_value"' : '')
							. ' />'
						. pathwell_show_custom_field('pathwell_options_field_'.esc_attr($name).'_slider',
													$field,
													pathwell_is_inherit($field['val']) ? '' : $field['val']);
			
		}
		
		$output .= ($inherit_allow
						? '<div class="pathwell_options_inherit_cover'.(!$inherit_state ? ' pathwell_hidden' : '').'">'
							. '<span class="pathwell_options_inherit_label">' . esc_html__('Inherit', 'pathwell') . '</span>'
							. '<input type="hidden" name="pathwell_options_inherit_'.esc_attr($name).'"'
									. ' value="'.esc_attr($inherit_state ? 'inherit' : '').'"'
									. ' />'
							. '</div>'
						: '')
					. ($field_data_present ? '</div>' : '')
					. (!empty($field['override']['desc']) || !empty($field['desc'])
						? '<div class="pathwell_options_item_description">'
							. (!empty($field['override']['desc']) 	// param 'desc' already processed with wp_kses()!
									? $field['override']['desc'] 
									: $field['desc'])
							. '</div>'
						: '')
				. ($field_data_present ? '</div>' : '')
			. '</div>';
		}
		return $output;
	}
}


// Show theme specific fields
function pathwell_show_custom_field($id, $field, $value) {
	$output = '';
	switch ($field['type']) {
		
		case 'mediamanager':
			wp_enqueue_media( );
			$title = empty($field['data_type']) || $field['data_type']=='image'
							? esc_html__( 'Choose Image', 'pathwell')
							: esc_html__( 'Choose Media', 'pathwell');
			$output .= '<button id="'.esc_attr($id).'"'
							. ' class="button mediamanager pathwell_media_selector"'
							. '	data-param="' . esc_attr($id) . '"'
							. '	data-choose="'.esc_attr(!empty($field['multiple']) ? esc_html__( 'Choose Images', 'pathwell') : $title).'"'
							. ' data-update="'.esc_attr(!empty($field['multiple']) ? esc_html__( 'Add to Gallery', 'pathwell') : $title).'"'
							. '	data-multiple="'.esc_attr(!empty($field['multiple']) ? '1' : '0').'"'
							. '	data-type="'.esc_attr(!empty($field['data_type']) ? $field['data_type'] : 'image').'"'
							. '	data-linked-field="'.esc_attr($field['linked_field_id']).'"'
							. '>'
							. (!empty($field['multiple'])
									? (empty($field['data_type']) || $field['data_type']=='image'
										? esc_html__( 'Add Images', 'pathwell')
										: esc_html__( 'Add Files', 'pathwell')
										)
									: esc_html($title)
								)
							. '</button>';
			$output .= '<span class="pathwell_options_field_preview">';
			$images = explode('|', $value);
			if (is_array($images)) {
				foreach ($images as $img)
					$output .= $img && !pathwell_is_inherit($img)
							? '<span>'
									. (in_array(pathwell_get_file_ext($img), array('gif', 'jpg', 'jpeg', 'png'))
											? '<img src="' . esc_url($img) . '" alt="' . esc_attr__('image', 'pathwell') . '">'
											: '<a href="' . esc_attr($img) . '">' . esc_html(basename($img)) . '</a>'
										)
								. '</span>' 
							: '';
			}
			$output .= '</span>';
			break;

		case 'icons':
			$icons_type = !empty($field['style']) 
							? $field['style'] 
							: pathwell_get_theme_setting('icons_type');
			if (empty($field['return']))
				$field['return'] = 'full';
			$pathwell_icons = $icons_type=='images'
								? pathwell_get_list_images()
								: pathwell_array_from_list(pathwell_get_list_icons());
			if (is_array($pathwell_icons)) {
				if (!empty($field['button']))
					$output .= '<span id="'.esc_attr($id).'"'
									. ' class="pathwell_list_icons_selector'
											. ($icons_type=='icons' && !empty($value) ? ' '.esc_attr($value) : '')
											.'"'
									. ' title="'.esc_attr__('Select icon', 'pathwell').'"'
									. ' data-style="'.($icons_type=='images' ? 'images' : 'icons').'"'
									. ($icons_type=='images' && !empty($value) 
										? ' style="background-image: url('.esc_url($field['return']=='slug' 
																							? $pathwell_icons[$value] 
																							: $value).');"' 
											: '')
								. '></span>';
				if (!empty($field['icons'])) {
					$output .= '<div class="pathwell_list_icons">'
								. '<input type="text" class="pathwell_list_icons_search" placeholder="'.esc_attr__('Search icon ...', 'pathwell').'">';
					foreach($pathwell_icons as $slug=>$icon) {
						$output .= '<span class="'.esc_attr($icons_type=='icons' ? $icon : $slug)
								. (($field['return']=='full' ? $icon : $slug) == $value ? ' pathwell_list_active' : '')
								. '"'
								. ' title="'.esc_attr($slug).'"'
								. ' data-icon="'.esc_attr($field['return']=='full' ? $icon : $slug).'"'
								. ($icons_type=='images' ? ' style="background-image: url('.esc_url($icon).');"' : '')
								. '></span>';
					}
					$output .= '</div>';
				}
			}
			break;

		case 'checklist':
			if (!empty($field['sortable']))
				wp_enqueue_script('jquery-ui-sortable', false, array('jquery', 'jquery-ui-core'), null, true);
			$output .= '<div class="pathwell_checklist pathwell_checklist_'.esc_attr($field['dir'])
						. (!empty($field['sortable']) ? ' pathwell_sortable' : '') 
						. '">';
			if (!is_array($value)) {
				if (!empty($value) && !pathwell_is_inherit($value)) parse_str(str_replace('|', '&', $value), $value);
				else $value = array();
			}
			// Sort options by values order
			if (!empty($field['sortable']) && is_array($value)) {
				$field['options'] = pathwell_array_merge($value, $field['options']);
			}
			foreach ($field['options'] as $k=>$v) {
				$output .= '<label class="pathwell_checklist_item_label' 
								. (!empty($field['sortable']) ? ' pathwell_sortable_item' : '') 
								. '">'
							. '<input type="checkbox" value="1" data-name="'.$k.'"'
								.( isset($value[$k]) && (int) $value[$k] == 1 ? ' checked="checked"' : '')
								.' />'
							. (substr($v, 0, 4)=='http' ? '<img src="'.esc_url($v).'">' : esc_html($v))
						. '</label>';
			}
			$output .= '</div>';
			break;

		case 'slider':
		case 'range':
			wp_enqueue_script('jquery-ui-slider', false, array('jquery', 'jquery-ui-core'), null, true);
			$is_range  = $field['type'] == 'range';
			$field_min = !empty($field['min']) ? $field['min'] : 0;
			$field_max = !empty($field['max']) ? $field['max'] : 100;
			$field_step= !empty($field['step']) ? $field['step'] : 1;
			$field_val = !empty($value) 
							? ($value . ($is_range && strpos($value, ',')===false ? ','.$field_max : ''))
							: ($is_range ? $field_min.','.$field_max : $field_min);
			$output .= '<div id="'.esc_attr($id).'"'
							. ' class="pathwell_range_slider"'
							. ' data-range="' . esc_attr($is_range ? 'true' : 'min') . '"'
							. ' data-min="' . esc_attr($field_min) . '"'
							. ' data-max="' . esc_attr($field_max) . '"'
							. ' data-step="' . esc_attr($field_step) . '"'
							. '>'
							. '<span class="pathwell_range_slider_label pathwell_range_slider_label_min">'
								. esc_html($field_min)
							. '</span>'
							. '<span class="pathwell_range_slider_label pathwell_range_slider_label_max">'
								. esc_html($field_max)
							. '</span>';
			$values = explode(',', $field_val);
			for ($i=0; $i < count($values); $i++) {
				$output .= '<span class="pathwell_range_slider_label pathwell_range_slider_label_cur">'
								. esc_html($values[$i])
							. '</span>';
			}
			$output .= '</div>';
			break;

		case 'text_editor':
			if (function_exists('wp_enqueue_editor')) wp_enqueue_editor();
			ob_start();
			wp_editor( $value, $id, array(
				'default_editor' => 'tmce',
				'wpautop' => isset($field['wpautop']) ? $field['wpautop'] : false,
				'teeny' => isset($field['teeny']) ? $field['teeny'] : false,
				'textarea_rows' => isset($field['rows']) && $field['rows'] > 1 ? $field['rows'] : 10,
				'editor_height' => 16*(isset($field['rows']) && $field['rows'] > 1 ? (int) $field['rows'] : 10),
				'tinymce' => array(
					'resize'             => false,
					'wp_autoresize_on'   => false,
					'add_unload_trigger' => false
				)
			));
			$editor_html = ob_get_contents();
			ob_end_clean();
			$output .= '<div class="pathwell_text_editor">' . $editor_html . '</div>';
			break;

			
		case 'scheme_editor':
			if (!is_array($value)) break;
			if (empty($field['colorpicker'])) $field['colorpicker'] = 'internal';
			$output .= '<div class="pathwell_scheme_editor">';
			// Select scheme
			$output .= '<select class="pathwell_scheme_editor_selector">';
			foreach ($value as $scheme=>$v)
				$output .= '<option value="' . esc_attr($scheme) . '">' . esc_html($v['title']) . '</option>';
			$output .= '</select>';
			// Select type
			$output .= '<div class="pathwell_scheme_editor_type">'
							. '<div class="pathwell_scheme_editor_row">'
								. '<span class="pathwell_scheme_editor_row_cell">'
									. esc_html__('Editor type', 'pathwell')
								. '</span>'
								. '<span class="pathwell_scheme_editor_row_cell pathwell_scheme_editor_row_cell_span">'
									.'<label>'
										. '<input name="pathwell_scheme_editor_type" type="radio" value="simple" checked="checked"> '
										. esc_html__('Simple', 'pathwell')
									. '</label>'
									. '<label>'
										. '<input name="pathwell_scheme_editor_type" type="radio" value="advanced"> '
										. esc_html__('Advanced', 'pathwell')
									. '</label>'
								. '</span>'
							. '</div>'
						. '</div>';
			// Colors
			$groups = pathwell_storage_get('scheme_color_groups');
			$colors = pathwell_storage_get('scheme_color_names');
			$output .= '<div class="pathwell_scheme_editor_colors">';
			foreach ($value as $scheme=>$v) {
				$output .= '<div class="pathwell_scheme_editor_header">'
								. '<span class="pathwell_scheme_editor_header_cell"></span>';
				foreach ($groups as $group_name=>$group_data) {
					$output .= '<span class="pathwell_scheme_editor_header_cell" title="'.esc_attr($group_data['description']).'">' 
								. esc_html($group_data['title'])
								. '</span>';
				}
				$output .= '</div>';
				foreach ($colors as $color_name=>$color_data) {
					$output .= '<div class="pathwell_scheme_editor_row">'
								. '<span class="pathwell_scheme_editor_row_cell" title="'.esc_attr($color_data['description']).'">'
								. esc_html($color_data['title'])
								. '</span>';
					foreach ($groups as $group_name=>$group_data) {
						$slug = $group_name == 'main' 
									? $color_name 
									: str_replace('text_', '', "{$group_name}_{$color_name}");
						$output .= '<span class="pathwell_scheme_editor_row_cell">'
									. (isset($v['colors'][$slug])
										? "<input type=\"text\" name=\"{$slug}\" class=\"".($field['colorpicker']=='tiny' ? 'tinyColorPicker' : 'iColorPicker')."\" value=\"".esc_attr($v['colors'][$slug])."\">"
										: ''
										)
									. '</span>';
					}
					$output .= '</div>';
				}
				break;
			}
			$output .= '</div>'
					. '</div>';
			break;
	}
	return apply_filters('pathwell_filter_show_custom_field', $output, $id, $field, $value);
}



// Save options
if (!function_exists('pathwell_options_save')) {
	add_action('after_setup_theme', 'pathwell_options_save', 4);
	function pathwell_options_save() {

		if (!isset($_REQUEST['page']) || $_REQUEST['page']!='theme_options' || pathwell_get_value_gp('pathwell_nonce')=='') return;

		// verify nonce
		if ( !wp_verify_nonce( pathwell_get_value_gp('pathwell_nonce'), admin_url() ) ) {
			pathwell_add_admin_message(esc_html__('Bad security code! Options are not saved!', 'pathwell'), 'error', true);
			return;
		}

		// Check permissions
		if (!current_user_can('manage_options')) {
			pathwell_add_admin_message(esc_html__('Manage options is denied for the current user! Options are not saved!', 'pathwell'), 'error', true);
			return;
		}

		// Save options
		$options = pathwell_storage_get('options');
		$values = get_theme_mods();
		$external_storages = array();
		foreach ($options as $k=>$v) {
			// Skip non-data options - sections, info, etc.
			if (!isset($v['std'])) continue;
			// Get option value from POST
			$value = isset($_POST['pathwell_options_field_' . $k])
							? pathwell_get_value_gp('pathwell_options_field_' . $k)
							: ($v['type']=='checkbox' ? 0 : '');
			// Individual options processing
			if ($k == 'custom_logo' && !empty($value) && (int) $value == 0) {
				$value = attachment_url_to_postid(pathwell_clear_thumb_size($value));
				if (empty($value)) $value = get_theme_mod($k);	//$values[$k];
			}
			// Save to the result array
			if (!empty($v['type']) && $v['type']!='hidden' && (empty($v['hidden']) || !$v['hidden']) && $value != pathwell_get_theme_option_std($k, $v['std'])) {
				$values[$k] = $value;
			} else if (isset($values[$k])) {
				unset($values[$k]);
			}
			// External plugin's options
			if (!empty($v['options_storage'])) {
				if (!isset($external_storages[$v['options_storage']]))
					$external_storages[$v['options_storage']] = array();
				$external_storages[$v['options_storage']][$k] = $value;
			}
		}

		// Update options in the external storages
		foreach ($external_storages as $storage_name => $storage_values) {
			$storage = get_option($storage_name, false);
			if (is_array($storage)) {
				foreach ($storage_values as $k=>$v)
					$storage[$k] = $v;
				update_option($storage_name, apply_filters('pathwell_filter_options_save', $storage, $storage_name));
			}
		}

		// Update Theme Mods (internal Theme Options)
		$stylesheet_slug = get_option('stylesheet');
		update_option("theme_mods_{$stylesheet_slug}", apply_filters('pathwell_filter_options_save', $values, 'theme_mods'));

		do_action('pathwell_action_just_save_options');

		// Store new schemes colors
		if (!empty($values['scheme_storage'])) {
			$schemes = pathwell_unserialize($values['scheme_storage']);
			if (is_array($schemes) && count($schemes) > 0) 
				pathwell_storage_set('schemes', $schemes);
		}
		
		// Store new fonts parameters
		$fonts = pathwell_get_theme_fonts();
		foreach ($fonts as $tag=>$v) {
			foreach ($v as $css_prop=>$css_value) {
				if (in_array($css_prop, array('title', 'description'))) continue;
				if (isset($values["{$tag}_{$css_prop}"])) $fonts[$tag][$css_prop] = $values["{$tag}_{$css_prop}"];
			}
		}
		pathwell_storage_set('theme_fonts', $fonts);

		// Update ThemeOptions save timestamp
		$stylesheet_time = time();
		update_option("pathwell_options_timestamp_{$stylesheet_slug}", $stylesheet_time);

		// Sinchronize theme options between child and parent themes
		if (pathwell_get_theme_setting('duplicate_options') == 'both') {
			$theme_slug = get_option('template');
			if ($theme_slug != $stylesheet_slug) {
				pathwell_customizer_duplicate_theme_options($stylesheet_slug, $theme_slug, $stylesheet_time);
			}
		}

		// Apply action - moved to the delayed state (see below) to load all enabled modules and apply changes after
		// Attention! Don't remove comment the line below!
		// Not need here: do_action('pathwell_action_save_options');
		update_option('pathwell_action', 'pathwell_action_save_options');

		// Return result
		pathwell_add_admin_message(esc_html__('Options are saved', 'pathwell'));
		wp_redirect(get_admin_url(null, 'themes.php?page=theme_options'));
		exit();

		// pathwell_add_admin_message(esc_html__('Options are saved', 'pathwell'));
		// if (!empty($_SERVER['HTTP_REFERER'])) {
		// 	wp_redirect(wp_unslash($_SERVER['HTTP_REFERER']));
		// 	exit();
		// }
	}
}


//-------------------------------------------------------
//-- Delayed action from previous session
//-- (after save options)
//-- to save new CSS, etc.
//-------------------------------------------------------
if ( !function_exists('pathwell_do_delayed_action') ) {
	add_action( 'after_setup_theme', 'pathwell_do_delayed_action' );
	function pathwell_do_delayed_action() {
		if (($action = get_option('pathwell_action')) != '') {
		    do_action($action);
			update_option('pathwell_action', '');
		}
	}
}


// Refresh data in the linked field
// according the main field value
if (!function_exists('pathwell_refresh_linked_data')) {
	function pathwell_refresh_linked_data($value, $linked_name) {
		if ($linked_name == 'parent_cat') {
			$tax = pathwell_get_post_type_taxonomy($value);
			$terms = !empty($tax) ? pathwell_get_list_terms(false, $tax) : array();
			$terms = pathwell_array_merge(array(0 => esc_html__('- Select category -', 'pathwell')), $terms);
			pathwell_storage_set_array2('options', $linked_name, 'options', $terms);
		}
	}
}


// AJAX: Refresh data in the linked fields
if (!function_exists('pathwell_callback_get_linked_data')) {
	add_action('wp_ajax_pathwell_get_linked_data', 		'pathwell_callback_get_linked_data');
	add_action('wp_ajax_nopriv_pathwell_get_linked_data','pathwell_callback_get_linked_data');
	function pathwell_callback_get_linked_data() {
		if ( !wp_verify_nonce( pathwell_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
		$chg_name = wp_kses_data(wp_unslash($_REQUEST['chg_name']));
		$chg_value = wp_kses_data(wp_unslash($_REQUEST['chg_value']));
		$response = array('error' => '');
		if ($chg_name == 'post_type') {
			$tax = pathwell_get_post_type_taxonomy($chg_value);
			$terms = !empty($tax) ? pathwell_get_list_terms(false, $tax) : array();
			$response['list'] = pathwell_array_merge(array(0 => esc_html__('- Select category -', 'pathwell')), $terms);
		}
		echo json_encode($response);
		die();
	}
}
?>