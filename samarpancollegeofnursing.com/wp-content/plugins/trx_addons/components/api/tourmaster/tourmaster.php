<?php
/**
 * Plugin support: Tour Master
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.38
 */

if (!defined('TRX_ADDONS_TOURMASTER_CPT_TOUR'))			define('TRX_ADDONS_TOURMASTER_CPT_TOUR', 			'tour');
if (!defined('TRX_ADDONS_TOURMASTER_CPT_TOUR_COUPON'))	define('TRX_ADDONS_TOURMASTER_CPT_TOUR_COUPON',		'tour_coupon');
if (!defined('TRX_ADDONS_TOURMASTER_CPT_TOUR_SERVICE'))	define('TRX_ADDONS_TOURMASTER_CPT_TOUR_SERVICE',	'tour_service');
if (!defined('TRX_ADDONS_TOURMASTER_TAX_TOUR_CATEGORY'))define('TRX_ADDONS_TOURMASTER_TAX_TOUR_CATEGORY',	'tour_category');
if (!defined('TRX_ADDONS_TOURMASTER_TAX_TOUR_TAG'))		define('TRX_ADDONS_TOURMASTER_TAX_TOUR_TAG',		'tour_tag');

// Check if plugin installed and activated
if ( !function_exists( 'trx_addons_exists_tourmaster' ) ) {
	function trx_addons_exists_tourmaster() {
		return defined( 'TOURMASTER_LOCAL' );
	}
}

// Return true, if current page is any tourmaster page
if ( !function_exists( 'trx_addons_is_tourmaster_page' ) ) {
	function trx_addons_is_tourmaster_page() {
		$rez = false;
		if (trx_addons_exists_tourmaster()) {
			$rez = (is_single() && in_array(get_query_var('post_type'),
												array(TRX_ADDONS_TOURMASTER_CPT_TOUR,
													TRX_ADDONS_TOURMASTER_CPT_TOUR_COUPON,
													TRX_ADDONS_TOURMASTER_CPT_TOUR_SERVICE)))
					|| (is_home() && isset($_GET['tour-search']))
					|| (is_home() && isset($_GET['tourmaster-payment']))
					|| is_post_type_archive(TRX_ADDONS_TOURMASTER_CPT_TOUR) 
					|| is_post_type_archive(TRX_ADDONS_TOURMASTER_CPT_TOUR_COUPON) 
					|| is_post_type_archive(TRX_ADDONS_TOURMASTER_CPT_TOUR_SERVICE) 
					|| is_tax(TRX_ADDONS_TOURMASTER_TAX_TOUR_CATEGORY)
					|| is_tax(TRX_ADDONS_TOURMASTER_TAX_TOUR_TAG);
		}
		return $rez;
	}
}


// Return taxonomy for current post type (this post_type have 2+ taxonomies)
if ( !function_exists( 'trx_addons_tourmaster_post_type_taxonomy' ) ) {
	add_filter( 'trx_addons_filter_post_type_taxonomy',	'trx_addons_tourmaster_post_type_taxonomy', 10, 2 );
	function trx_addons_tourmaster_post_type_taxonomy($tax='', $post_type='') {
		if ($post_type == TRX_ADDONS_TOURMASTER_CPT_TOUR)
			$tax = TRX_ADDONS_TOURMASTER_TAX_TOUR_CATEGORY;
		return $tax;
	}
}

// Return link to main hotels page for the breadcrumbs
if ( !function_exists( 'trx_addons_tourmaster_get_blog_all_posts_link' ) ) {
	add_filter('trx_addons_filter_get_blog_all_posts_link', 'trx_addons_tourmaster_get_blog_all_posts_link', 10, 2);
	function trx_addons_tourmaster_get_blog_all_posts_link($link='', $args=array()) {
		if (empty($link) && trx_addons_is_tourmaster_page()) {
			if (($url = trx_addons_tourmaster_get_tours_page_link()) != '') {
				$id = trx_addons_tourmaster_get_tours_page_id();
				$title = $id ? get_the_title($id) : __('Tours', 'trx_addons');
				$link = '<a href="'.esc_url($url).'">'. esc_html($title).'</a>';
			}
		}
		return $link;
	}
}

// Return tours page ID
if ( !function_exists( 'trx_addons_tourmaster_get_tours_page_id' ) ) {
	function trx_addons_tourmaster_get_tours_page_id() {
		return apply_filters('trx_addons_filter_get_all_posts_page_id', 0, 'tourmaster');
	}
}

// Return hotels page link
if ( !function_exists( 'trx_addons_tourmaster_get_tours_page_link' ) ) {
	function trx_addons_tourmaster_get_tours_page_link() {
		$id = trx_addons_tourmaster_get_tours_page_id();
		return $id > 0 ? get_permalink($id) : get_post_type_archive_link(TRX_ADDONS_TOURMASTER_CPT_TOUR);
	}
}

// Return current page title
if ( !function_exists( 'trx_addons_tourmaster_get_blog_title' ) ) {
	add_filter( 'trx_addons_filter_get_blog_title', 'trx_addons_tourmaster_get_blog_title');
	function trx_addons_tourmaster_get_blog_title($title='') {
		if (trx_addons_exists_tourmaster()) {
			if (is_post_type_archive(TRX_ADDONS_TOURMASTER_CPT_TOUR) || is_post_type_archive(TRX_ADDONS_TOURMASTER_CPT_TOUR_SERVICE)) {
				$id = trx_addons_tourmaster_get_tours_page_id();
				$title = $id ? get_the_title($id) : __('Tours', 'trx_addons');
			} else if (is_home() && isset($_GET['tour-search']))
				$title = __('Tour search', 'trx_addons');
			else if (is_home() && isset($_GET['tourmaster-payment']))
				$title = __('Tour payment', 'trx_addons');
		}
		return $title;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check plugin in the required plugins
if ( !function_exists( 'trx_addons_tourmaster_importer_required_plugins' ) ) {
	if (is_admin()) add_filter( 'trx_addons_filter_importer_required_plugins',	'trx_addons_tourmaster_importer_required_plugins', 10, 2 );
	function trx_addons_tourmaster_importer_required_plugins($not_installed='', $list='') {
		if (strpos($list, 'tourmaster')!==false && !trx_addons_exists_tourmaster() )
			$not_installed .= '<br>' . esc_html__('Tour Master', 'trx_addons');
		return $not_installed;
	}
}

// Set plugin's specific importer options
if ( !function_exists( 'trx_addons_tourmaster_importer_set_options' ) ) {
	if (is_admin()) add_filter( 'trx_addons_filter_importer_options',	'trx_addons_tourmaster_importer_set_options' );
	function trx_addons_tourmaster_importer_set_options($options=array()) {
		if ( trx_addons_exists_tourmaster() && in_array('tourmaster', $options['required_plugins']) ) {
			$options['additional_options'][] = 'tourmaster_general';					// Add slugs to export options for this plugin
			$options['additional_options'][] = 'tourmaster_color';
			$options['additional_options'][] = 'tourmaster_plugin';
			// Do not export this option, because it contain secret keys
			//$options['additional_options'][] = 'tourmaster_payment';
			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_tourmaster'] = str_replace('name.ext', 'tourmaster.txt', $v['file_with_']);
				}
			}
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'trx_addons_tourmaster_importer_show_params' ) ) {
	if (is_admin()) add_action( 'trx_addons_action_importer_params',	'trx_addons_tourmaster_importer_show_params', 10, 1 );
	function trx_addons_tourmaster_importer_show_params($importer) {
		if ( trx_addons_exists_tourmaster() && in_array('tourmaster', $importer->options['required_plugins']) ) {
			$importer->show_importer_params(array(
				'slug' => 'tourmaster',
				'title' => esc_html__('Import Tour Master', 'trx_addons'),
				'part' => 0
			));
		}
	}
}

// Import posts
if ( !function_exists( 'trx_addons_tourmaster_importer_import' ) ) {
	if (is_admin()) add_action( 'trx_addons_action_importer_import',	'trx_addons_tourmaster_importer_import', 10, 2 );
	function trx_addons_tourmaster_importer_import($importer, $action) {
		if ( trx_addons_exists_tourmaster() && in_array('tourmaster', $importer->options['required_plugins']) ) {
			if ( $action == 'import_tourmaster' ) {
				$importer->response['start_from_id'] = 0;
				$importer->import_dump('tourmaster', esc_html__('Tour Master data', 'trx_addons'));
			}
		}
	}
}

// Check if the row will be imported
if ( !function_exists( 'trx_addons_tourmaster_importer_check_row' ) ) {
	if (is_admin()) add_filter('trx_addons_filter_importer_import_row', 'trx_addons_tourmaster_importer_check_row', 9, 4);
	function trx_addons_tourmaster_importer_check_row($flag, $table, $row, $list) {
		if ($flag || strpos($list, 'tourmaster')===false) return $flag;
		if ( trx_addons_exists_tourmaster() ) {
			if ($table == 'posts')
				$flag = in_array($row['post_type'], array(TRX_ADDONS_TOURMASTER_CPT_TOUR, TRX_ADDONS_TOURMASTER_CPT_TOUR_COUPON, TRX_ADDONS_TOURMASTER_CPT_TOUR_SERVICE));
		}
		return $flag;
	}
}

// Display import progress
if ( !function_exists( 'trx_addons_tourmaster_importer_import_fields' ) ) {
	if (is_admin()) add_action( 'trx_addons_action_importer_import_fields',	'trx_addons_tourmaster_importer_import_fields', 10, 1 );
	function trx_addons_tourmaster_importer_import_fields($importer) {
		if ( trx_addons_exists_tourmaster() && in_array('tourmaster', $importer->options['required_plugins']) ) {
			$importer->show_importer_fields(array(
				'slug'=>'tourmaster', 
				'title' => esc_html__('Tour Master', 'trx_addons')
				)
			);
		}
	}
}

// Export posts
if ( !function_exists( 'trx_addons_tourmaster_importer_export' ) ) {
	if (is_admin()) add_action( 'trx_addons_action_importer_export',	'trx_addons_tourmaster_importer_export', 10, 1 );
	function trx_addons_tourmaster_importer_export($importer) {
		if ( trx_addons_exists_tourmaster() && in_array('tourmaster', $importer->options['required_plugins']) ) {
			trx_addons_fpc($importer->export_file_dir('tourmaster.txt'), serialize( array(
				"tourmaster_order"	=> $importer->export_dump("tourmaster_order"),
				"tourmaster_review"	=> $importer->export_dump("tourmaster_review")
				) )
			);
		}
	}
}

// Display exported data in the fields
if ( !function_exists( 'trx_addons_tourmaster_importer_export_fields' ) ) {
	if (is_admin()) add_action( 'trx_addons_action_importer_export_fields',	'trx_addons_tourmaster_importer_export_fields', 10, 1 );
	function trx_addons_tourmaster_importer_export_fields($importer) {
		if ( trx_addons_exists_tourmaster() && in_array('tourmaster', $importer->options['required_plugins']) ) {
			$importer->show_exporter_fields(array(
				'slug'	=> 'tourmaster',
				'title' => esc_html__('Tour Master', 'trx_addons')
				)
			);
		}
	}
}


// VC support
//------------------------------------------------------------------------

// Add [tourmaster_tour_xxx] to the VC shortcodes list
if (!function_exists('trx_addons_tourmaster_sc_add_in_vc')) {
	function trx_addons_tourmaster_sc_add_in_vc() {
	
		if (!trx_addons_exists_visual_composer() || !trx_addons_exists_tourmaster()) return;

		vc_lean_map( "tourmaster_tour_category", 'trx_addons_tourmaster_sc_add_in_vc_params_ttc');
		class WPBakeryShortCode_Tourmaster_Tour_Category extends WPBakeryShortCode {}

		vc_lean_map( "tourmaster_tour_search", 'trx_addons_tourmaster_sc_add_in_vc_params_tts');
		class WPBakeryShortCode_Tourmaster_Tour_Search extends WPBakeryShortCode {}

		vc_lean_map( "tourmaster_tour", 'trx_addons_tourmaster_sc_add_in_vc_params_tt');
		class WPBakeryShortCode_Tourmaster_Tour extends WPBakeryShortCode {}
	}
	add_action('init', 'trx_addons_tourmaster_sc_add_in_vc', 20);
}

// Return params for [tourmaster_tour_category]
if (!function_exists('trx_addons_tourmaster_sc_add_in_vc_params_ttc')) {
	function trx_addons_tourmaster_sc_add_in_vc_params_ttc() {
		return apply_filters('trx_addons_sc_map', array(
				"base" => "tourmaster_tour_category",
				"name" => esc_html__("Tourmaster Categories", "trx_addons"),
				"description" => esc_html__("Insert the tour's categories list", "trx_addons"),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				'icon' => 'icon_trx_sc_tourmaster_tour_category',
				"class" => "trx_sc_single trx_sc_tourmaster_tour_category",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					// Group 'General'
					array(
						"param_name" => "filter-type",
						"heading" => esc_html__("Filter Type", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-4',
						"admin_label" => true,
						"value" => array_flip(trx_addons_get_list_taxonomies(false, TRX_ADDONS_TOURMASTER_CPT_TOUR)),
						"std" => TRX_ADDONS_TOURMASTER_TAX_TOUR_CATEGORY,
						"save_always" => true,
						"type" => "dropdown"
					),
					array(
						"param_name" => "num-fetch",
						"heading" => esc_html__('Display Number', 'trx_addons'), 
						'edit_field_class' => 'vc_col-sm-4',
						"save_always" => true,
						"std" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "column-size",
						'heading' => esc_html__('Columns', 'trx_addons'), 
						'edit_field_class' => 'vc_col-sm-4',
						'value' => array( "60"=>"1", "30"=>"2", "20"=>"3", "15"=>"4", "12"=>"5" ),
						"save_always" => true,
						'std' => 3,
						'type' => 'dropdown',
					),
					array(
						"param_name" => "style",
						'heading' => esc_html__('Style', 'trx_addons'), 
						'edit_field_class' => 'vc_col-sm-6 vc_new_row',
						'value' => array_flip(array(
							'widget' => esc_html__('Widget', 'trx_addons'),
							'grid' => esc_html__('Grid', 'trx_addons'),
							'grid-2' => esc_html__('Grid 2', 'trx_addons'),
						)),
						'std' => 'widget',
						"save_always" => true,
						'type' => 'dropdown',
					),
					array(
						"param_name" => "thumbnail-size",
						'heading' => esc_html__('Thumbnail Size', 'trx_addons'), 
						'edit_field_class' => 'vc_col-sm-6',
						'value' => array_flip(trx_addons_get_list_thumbnail_sizes()),
						'std' => 'thumbnail',
						'type' => 'dropdown',
					),
					array(
						"param_name" => "orderby",
						'heading' => esc_html__('Order by', 'trx_addons'), 
						'edit_field_class' => 'vc_col-sm-6 vc_new_row',
						'value' => array_flip(array(
							'name' => esc_html__('Name', 'trx_addons'), 
							'slug' => esc_html__('Slug', 'trx_addons'), 
							'term_id' => esc_html__('Term ID', 'trx_addons'), 
						)),
						'std' => 'name',
						"save_always" => true,
						'type' => 'dropdown',
					),
					array(
						"param_name" => "order",
						'heading' => esc_html__('Order', 'trx_addons'), 
						'edit_field_class' => 'vc_col-sm-6',
						'value' => array_flip(array(
							'asc' => esc_html__('Ascending', 'trx_addons'), 
							'desc' => esc_html__('Descending', 'trx_addons'), 
						)),
						'std' => 'asc',
						"save_always" => true,
						'type' => 'dropdown',
					),
				)
			), 'tourmaster_tour_category' );
	}
}

// Return params for [tourmaster_tour]
if (!function_exists('trx_addons_tourmaster_sc_add_in_vc_params_tt')) {
	function trx_addons_tourmaster_sc_add_in_vc_params_tt() {
		return apply_filters('trx_addons_sc_map', array(
				"base" => "tourmaster_tour",
				"name" => esc_html__("Tourmaster Tours", "trx_addons"),
				"description" => esc_html__("Insert the tours list", "trx_addons"),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				'icon' => 'icon_trx_sc_tourmaster_tour',
				"class" => "trx_sc_single trx_sc_tourmaster_tour",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					// Tab "General"
					array(
						"param_name" => "category",
						"heading" => esc_html__("Category", 'trx_addons'),
						"description" => wp_kses_data( __("You can use Ctrl/Command button to select multiple items or remove the selected item. Leave this field blank to select all items in the list", 'trx_addons') ),
						"group" => esc_html__("General", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						"value" => array_flip(trx_addons_get_list_terms(false, TRX_ADDONS_TOURMASTER_TAX_TOUR_CATEGORY, array(
															'hide_empty' => 1, 'return_key' => 'slug'))),
						"save_always" => true,
						"std" => "",
						"multiple" => true,
						"size" => 5,
						"type" => "select"
					),
					array(
						"param_name" => "tag",
						"heading" => esc_html__("Tag", 'trx_addons'),
						"description" => wp_kses_data( __("You can use Ctrl/Command button to select multiple items or remove the selected item. Leave this field blank to select all items in the list", 'trx_addons') ),
						"group" => esc_html__("General", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						"value" => array_flip(trx_addons_get_list_terms(false, TRX_ADDONS_TOURMASTER_TAX_TOUR_TAG, array(
															'hide_empty' => 1, 'return_key' => 'slug'))),
						"save_always" => true,
						"std" => "",
						"multiple" => true,
						"size" => 5,
						"type" => "select"
					),
					array(
						"param_name" => "discount-status",
						'heading' => esc_html__('Discount Status', 'trx_addons'), 
						"group" => esc_html__("General", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6 vc_new_row',
						'value' => array_flip(array(
													'all' => esc_html__('All', 'trx_addons'), 
													'discount' => esc_html__('Discounted Tour (tour with discount text filled)', 'trx_addons'), 
													)),
						"save_always" => true,
						'std' => 'all',
						'type' => 'dropdown',
					),
					array(
						"param_name" => "num-fetch",
						"heading" => esc_html__('Display Number', 'trx_addons'), 
						"group" => esc_html__("General", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						"save_always" => true,
						"std" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						'heading' => esc_html__('Order By', 'trx_addons'), 
						"group" => esc_html__("General", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6 vc_new_row',
						'value' => array_flip(array(
													'date' => esc_html__('Publish Date', 'trx_addons'), 
													'title' => esc_html__('Title', 'trx_addons'), 
													'rand' => esc_html__('Random', 'trx_addons'), 
													'menu_order' => esc_html__('Menu Order', 'trx_addons'), 
													'price' => esc_html__('Price', 'trx_addons'), 
													'duration' => esc_html__('Duration', 'trx_addons'), 
													'popularity' => esc_html__('Popularity ( View Count )', 'trx_addons'), 
													'rating' => esc_html__('Rating ( Score )', 'trx_addons'), 
													)),
						"save_always" => true,
						'std' => 'date',
						'type' => 'dropdown',
					),
					array(
						"param_name" => "order",
						'heading' => esc_html__('Order', 'trx_addons'), 
						"group" => esc_html__("General", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						'value' => array_flip(array(
													'asc' => esc_html__('Ascending', 'trx_addons'), 
													'desc' => esc_html__('Descending', 'trx_addons'), 
													)),
						"save_always" => true,
						'std' => 'desc',
						'type' => 'dropdown',
					),
					array(
						"param_name" => "pagination",
						'heading' => esc_html__('Pagination', 'trx_addons'), 
						"group" => esc_html__("General", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6 vc_new_row',
						'value' => array_flip(array(
													'none' => esc_html__('None', 'trx_addons'), 
													'page' => esc_html__('Page', 'trx_addons'), 
													'load-more' => esc_html__('Load More', 'trx_addons'), 
													)),
						"save_always" => true,
						'std' => 'none',
						'type' => 'dropdown',
					),
					array(
						"param_name" => "pagination-style",
						'heading' => esc_html__('Pagination Style', 'trx_addons'), 
						"group" => esc_html__("General", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						'dependency' => array(
							'element' => 'pagination',
							'value' => array('page')
						),
						'value' => array_flip(array(
													'default' => esc_html__('Default', 'trx_addons'),
													'plain' => esc_html__('Plain', 'trx_addons'),
													'rectangle' => esc_html__('Rectangle', 'trx_addons'),
													'rectangle-border' => esc_html__('Rectangle Border', 'trx_addons'),
													'round' => esc_html__('Round', 'trx_addons'),
													'round-border' => esc_html__('Round Border', 'trx_addons'),
													'circle' => esc_html__('Circle', 'trx_addons'),
													'circle-border' => esc_html__('Circle Border', 'trx_addons'),
													)),
						"save_always" => true,
						'std' => 'default',
						'type' => 'dropdown',
					),
					array(
						"param_name" => "pagination-align",
						'heading' => esc_html__('Pagination Alignment', 'trx_addons'), 
						"group" => esc_html__("General", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						'dependency' => array(
							'element' => 'pagination',
							'value' => array('page')
						),
						'value' => array_flip(trx_addons_get_list_sc_title_aligns(false, false)),
						"save_always" => true,
						'std' => 'left',
						'type' => 'dropdown',
					),
					// Tab "Filter"
					array(
						"param_name" => "enable-order-filterer",
						'heading' => esc_html__('Order Filterer', 'trx_addons'), 
						"group" => esc_html__("Filterer", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						'value' => array_flip(array(
													'enable' => esc_html__('Enable', 'trx_addons'), 
													'disable' => esc_html__('Disable', 'trx_addons'), 
													)),
						"save_always" => true,
						'std' => 'disable',
						'type' => 'dropdown',
					),
					array(
						"param_name" => "order-filterer-list-style",
						'heading' => esc_html__('Order Filterer List Style', 'trx_addons'), 
						"group" => esc_html__("Filterer", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6 vc_new_row',
						'dependency' => array(
							'element' => 'enable-order-filterer',
							'value' => array('enable')
						),
						'value' => array_flip(array(
													'none' => esc_html__('None', 'trx_addons'),
													'full' => esc_html__('Full', 'trx_addons'),
													'full-with-frame' => esc_html__('Full With Frame', 'trx_addons'),
													'medium' => esc_html__('Medium', 'trx_addons'),
													'medium-with-frame' => esc_html__('Medium With Frame', 'trx_addons'),
													'widget' => esc_html__('Widget', 'trx_addons'),
													)),
						"save_always" => true,
						'std' => 'none',
						'type' => 'dropdown',
					),
					array(
						"param_name" => "order-filterer-list-style-thumbnail",
						'heading' => esc_html__('Order Filterer List Style Thumbnail', 'trx_addons'), 
						"group" => esc_html__("Filterer", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						'dependency' => array(
							'element' => 'enable-order-filterer',
							'value' => array('enable')
						),
						'value' => array_flip(trx_addons_get_list_thumbnail_sizes()),
						"save_always" => true,
						'std' => 'thumbnail',
						'type' => 'dropdown',
					),
					array(
						"param_name" => "order-filterer-grid-style",
						'heading' => esc_html__('Order Filterer Grid Style', 'trx_addons'), 
						"group" => esc_html__("Filterer", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6 vc_new_row',
						'dependency' => array(
							'element' => 'enable-order-filterer',
							'value' => array('enable')
						),
						'value' => array_flip(array(
													'none' => esc_html__('None', 'trx_addons'),
													'modern' => esc_html__('Modern', 'trx_addons'),
													'modern-no-space' => esc_html__('Modern No Space', 'trx_addons'),
													'grid' => esc_html__('Grid', 'trx_addons'),
													'grid-with-frame' => esc_html__('Grid With Frame', 'trx_addons'),
													'grid-no-space' => esc_html__('Grid No Space', 'trx_addons'),
													)),
						"save_always" => true,
						'std' => 'none',
						'type' => 'dropdown',
					),
					array(
						"param_name" => "order-filterer-grid-style-thumbnail",
						'heading' => esc_html__('Order Filterer Grid Style Thumbnail', 'trx_addons'), 
						"group" => esc_html__("Filterer", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						'dependency' => array(
							'element' => 'enable-order-filterer',
							'value' => array('enable')
						),
						'value' => array_flip(trx_addons_get_list_thumbnail_sizes()),
						"save_always" => true,
						'std' => 'thumbnail',
						'type' => 'dropdown',
					),
/*
					array(
						"param_name" => "filterer",
						'heading' => esc_html__('Category Filterer', 'trx_addons'), 
						"group" => esc_html__("Filterer", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6 vc_new_row',
						'dependency' => array(
							'element' => 'enable-order-filterer',
							'value' => array('disable')
						),
						'value' => array_flip(array(
													'none' => esc_html__('None', 'trx_addons'), 
													'text' => esc_html__('Filter Text Style', 'trx_addons'), 
													'button' => esc_html__('Filter Button Style', 'trx_addons'), 
													)),
						"save_always" => true,
						'std' => 'none',
						'type' => 'dropdown',
					),
					array(
						"param_name" => "filterer-align",
						'heading' => esc_html__('Filterer Alignment', 'trx_addons'), 
						"group" => esc_html__("Filterer", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						'dependency' => array(
							'element' => 'filterer',
							'value' => array('text', 'button')
						),
						'value' => array_flip(trx_addons_get_list_sc_title_aligns(false, false)),
						"save_always" => true,
						'std' => 'left',
						'type' => 'dropdown',
					),
*/
					// Tab "Style"
					array(
						"param_name" => "tour-style",
						"heading" => esc_html__("Tour Style", 'trx_addons'),
						"group" => esc_html__("Style", 'trx_addons'),
						"admin_label" => true,
						'save_always' => true,
						"std" => "full",
						"value" => apply_filters('trx_addons_sc_type', array(
														'full' => TOURMASTER_URL . '/images/tour-style/full.jpg',
														'full-with-frame' => TOURMASTER_URL . '/images/tour-style/full-with-frame.jpg',
														'medium' => TOURMASTER_URL . '/images/tour-style/medium.jpg',
														'medium-with-frame' => TOURMASTER_URL . '/images/tour-style/medium-with-frame.jpg',
														'modern' => TOURMASTER_URL . '/images/tour-style/modern.jpg',
														'modern-no-space' => TOURMASTER_URL . '/images/tour-style/modern-no-space.jpg',
														'grid' => TOURMASTER_URL . '/images/tour-style/grid.jpg',
														'grid-with-frame' => TOURMASTER_URL . '/images/tour-style/grid-with-frame.jpg',
														'grid-no-space' => TOURMASTER_URL . '/images/tour-style/grid-no-space.jpg',
														'widget' => TOURMASTER_URL . '/images/tour-style/widget.jpg',
														), 'tourmaster_tour' ),
						"mode" => 'inline',
						"return" => 'slug',
						"style" => "images",
						"type" => "icons"
					),
					array(
						"param_name" => "column-size",
						'heading' => esc_html__('Column Size', 'trx_addons'), 
						"group" => esc_html__("Style", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						'value' => array( "60"=>"1", "30"=>"2", "20"=>"3", "15"=>"4", "12"=>"5" ),
						"save_always" => true,
						'dependency' => array(
							'element' => 'tour-style',
							'value' => array('modern', 'modern-no-space', 'grid', 'grid-with-frame', 'grid-no-space')
						),
						"save_always" => true,
						'std' => 3,
						'type' => 'dropdown',
					),
					array(
						"param_name" => "thumbnail-size",
						'heading' => esc_html__('Thumbnail Size', 'trx_addons'), 
						"group" => esc_html__("Style", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						'value' => array_flip(trx_addons_get_list_thumbnail_sizes()),
						'dependency' => array(
							'element' => 'tour-style',
							'value' => array('modern', 'modern-no-space', 'grid', 'grid-with-frame', 'grid-no-space', 'full', 'full-with-frame', 'medium', 'medium-with-frame')
						),
						"save_always" => true,
						'std' => 'thumbnail',
						'type' => 'dropdown',
					),
					array(
						"param_name" => "layout",
						'heading' => esc_html__('Layout', 'trx_addons'), 
						"group" => esc_html__("Style", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6 vc_new_row',
						'value' => array_flip(array(
							'fitrows' => esc_html__('Fit Rows', 'trx_addons'),
							'carousel' => esc_html__('Carousel', 'trx_addons'),
							'masonry' => esc_html__('Masonry', 'trx_addons'),
						)),
						'dependency' => array(
							'element' => 'tour-style',
							'value' => array('modern', 'modern-no-space', 'grid', 'grid-with-frame', 'grid-no-space')
						),
						"save_always" => true,
						'std' => 'fitrows',
						'type' => 'dropdown',
					),
					array(
						"param_name" => "price-position",
						'heading' => esc_html__('Price Display Position', 'trx_addons'), 
						"group" => esc_html__("Style", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						'value' => array_flip(array(
							'right-title' => esc_html__('Right Side Of The Title', 'trx_addons'),
							'bottom-title' => esc_html__('Bottom Of The Title', 'trx_addons'),
							'bottom-bar' => esc_html__('As Bottom Bar', 'trx_addons'),
						)),
						'dependency' => array(
							'element' => 'tour-style',
							'value' => array('grid', 'grid-with-frame', 'grid-no-space')
						),
						"save_always" => true,
						'std' => 'right-title',
						'type' => 'dropdown',
					),
					array(
						"param_name" => "carousel-autoslide",
						'heading' => esc_html__('Autoslide Carousel', 'trx_addons'), 
						"group" => esc_html__("Style", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6 vc_new_row',
						'value' => array_flip(array(
							'enable' => esc_html__('Enable', 'trx_addons'),
							'disable' => esc_html__('Disable', 'trx_addons')
						)),
						'dependency' => array(
							'element' => 'layout',
							'value' => array('carousel')
						),
						"save_always" => true,
						'std' => 'enable',
						'type' => 'dropdown',
					),
					array(
						"param_name" => "carousel-navigation",
						'heading' => esc_html__('Carousel Navigation', 'trx_addons'), 
						"group" => esc_html__("Style", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						'value' => array_flip(array(
							'none' => esc_html__('None', 'trx_addons'),
							'navigation' => esc_html__('Only Navigation', 'trx_addons'),
							'bullet' => esc_html__('Only Bullet', 'trx_addons'),
							'both' => esc_html__('Both Navigation and Bullet', 'trx_addons'),
						)),
						'dependency' => array(
							'element' => 'layout',
							'value' => array('carousel')
						),
						"save_always" => true,
						'std' => 'navigation',
						'type' => 'dropdown',
					),
					array(
						"param_name" => "tour-info",
						'heading' => esc_html__('Tour Info', 'trx_addons'), 
						"group" => esc_html__("Style", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6 vc_new_row',
						'value' => array_flip(array(
							'duration-text' => esc_html__('Duration', 'trx_addons'),
							'availability' => esc_html__('Availability', 'trx_addons'),
							'departure-location' => esc_html__('Departure Location', 'trx_addons'),
							'return-location' => esc_html__('Return Location', 'trx_addons'),
							'minimum-age' => esc_html__('Minimum Age', 'trx_addons'),
							'maximum-people' => esc_html__('Maximum People', 'trx_addons'),
							'custom-excerpt' => esc_html__('Custom Excerpt ( In Tour Option )', 'trx_addons'),
						)),
						'dependency' => array(
							'element' => 'tour-style',
							'value' => array('modern', 'modern-no-space', 'grid', 'grid-with-frame', 'grid-no-space', 'full', 'full-with-frame', 'medium', 'medium-with-frame')
						),
						'std' => 'duration-text',
						"save_always" => true,
						'multiple' => true,
						'size' => 7,
						'type' => 'select',
					),
					array(
						"param_name" => "tour-rating",
						'heading' => esc_html__('Tour Rating', 'trx_addons'), 
						"group" => esc_html__("Style", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						'value' => array_flip(array(
							'enable' => esc_html__('Enable', 'trx_addons'),
							'disable' => esc_html__('Disable', 'trx_addons'),
						)),
						'dependency' => array(
							'element' => 'tour-style',
							'value' => array('full', 'full-with-frame', 'medium', 'medium-with-frame', 'grid', 'grid-with-frame', 'grid-no-space')
						),
						"save_always" => true,
						'std' => 'enable',
						'type' => 'dropdown',
					),
					array(
						"param_name" => "excerpt",
						'heading' => esc_html__('Excerpt Type', 'trx_addons'), 
						"group" => esc_html__("Style", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6 vc_new_row',
						'value' => array_flip(array(
									'specify-number' => esc_html__('Specify Number', 'trx_addons'),
									'show-all' => esc_html__('Show All ( use <!--more--> tag to cut the content )', 'trx_addons'),
									'none' => esc_html__('Disable Exceprt', 'trx_addons'),
						)),
						'dependency' => array(
							'element' => 'tour-style',
							'value' => array('full', 'full-with-frame', 'medium', 'medium-with-frame', 'grid', 'grid-with-frame', 'grid-no-space')
						),
						'std' => 'specify-number',
						"save_always" => true,
						'type' => 'dropdown',
					),
					array(
						"param_name" => "excerpt-number",
						'heading' => esc_html__('Excerpt Number', 'trx_addons'), 
						"group" => esc_html__("Style", 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						'dependency' => array(
							'element' => 'excerpt',
							'value' => array('specify-number')
						),
						'std' => 55,
						"save_always" => true,
						'type' => 'textfield',
					),
				)
			), 'tourmaster_tour' );
	}
}

// Return params for [tourmaster_tour_search]
if (!function_exists('trx_addons_tourmaster_sc_add_in_vc_params_tts')) {
	function trx_addons_tourmaster_sc_add_in_vc_params_tts() {
		return apply_filters('trx_addons_sc_map', array(
				"base" => "tourmaster_tour_search",
				"name" => esc_html__("Tourmaster Search", "trx_addons"),
				"description" => esc_html__("Insert the tour's search widget", "trx_addons"),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				'icon' => 'icon_trx_sc_tourmaster_tour_search',
				"class" => "trx_sc_single trx_sc_tourmaster_tour_search",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						'heading' => esc_html__('Title', 'trx_addons'), 
						'edit_field_class' => 'vc_col-sm-6 vc_new_row',
						'std' => "",
						"save_always" => true,
						'type' => 'textfield',
					),
					array(
						"param_name" => "style",
						'heading' => esc_html__('Select Style', 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						'value' => array_flip(array(
							'column' => esc_html__('Column', 'trx_addons'),
							'half' => esc_html__('Half', 'trx_addons'),
							'full' => esc_html__('Full', 'trx_addons'),
						)),
						"save_always" => true,
						'std' => 'column',
						'type' => 'dropdown'
					),
					array(
						"param_name" => "fields",
						'heading' => esc_html__('Select Fields', 'trx_addons'),
						'value' => array_flip(array(
							'keywords' => esc_html__('Keywords', 'trx_addons'),
							'tour_category' => esc_html__('Category', 'trx_addons'),
							'tour_tag' => esc_html__('Tag', 'trx_addons'),
							'duration' => esc_html__('Duration', 'trx_addons'),
							'date' => esc_html__('Date', 'trx_addons'),
							'min-price' => esc_html__('Min Price', 'trx_addons'),
							'max-price' => esc_html__('Max Price', 'trx_addons'),
						)),
						"admin_label" => true,
						"save_always" => true,
						'std' => 'keywords,duration,date',
						'multiple' => true,
						'size' => 6,
						'type' => 'select'
					),
					array(
						"param_name" => "padding-bottom",
						'heading' => esc_html__('Padding bottom', 'trx_addons'), 
						'edit_field_class' => 'vc_col-sm-6 vc_new_row',
						'std' => "30px",
						"save_always" => true,
						'type' => 'textfield',
					),
					array(
						"param_name" => "enable-rating-field",
						'heading' => esc_html__('Enable Rating', 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						'dependency' => array(
							'element' => 'style',
							'value' => array('full')
						),
						'value' => array_flip(array(
							'enable' => esc_html__('Enable', 'trx_addons'),
							'disable' => esc_html__('Disable', 'trx_addons'),
						)),
						"save_always" => true,
						'std' => 'disable',
						'type' => 'dropdown'
					),
					array(
						"param_name" => "with-frame",
						'heading' => esc_html__('Item Frame', 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6 vc_new_row',
						'value' => array_flip(array(
							'disable' => esc_html__('Disable', 'trx_addons'),
							'enable' => esc_html__('Color Background', 'trx_addons'),
							'image' => esc_html__('Image Background', 'trx_addons'),
						)),
						"save_always" => true,
						'std' => 'enable',
						'type' => 'dropdown'
					),
					array(
						"param_name" => "frame-background-color",
						'heading' => esc_html__('Frame Background Color', 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						'dependency' => array(
							'element' => 'with-frame',
							'value' => array('enable')
						),
						"save_always" => true,
						'std' => '',
						'type' => 'colorpicker'
					),
					array(
						"param_name" => "frame-background-image",
						'heading' => esc_html__('Frame Background Image', 'trx_addons'),
						'edit_field_class' => 'vc_col-sm-6',
						'dependency' => array(
							'element' => 'with-frame',
							'value' => array('image')
						),
						"save_always" => true,
						'std' => '',
						'type' => 'attach_image'
					),
				)
			), 'tourmaster_tour_search' );
	}
}
?>