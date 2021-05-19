/* global jQuery:false */

(function() {
	"use strict";
	
	// Uncomment next row to disable init VC prettyPhoto on the gallery images
	// to prevent conflict with the PrettyPhoto from WooCommerce 3+
	// Attention! In this case you need manually init some lightbox on the gallery images
	//window.vc_prettyPhoto = function() {};

	jQuery(document).on('action.init_shortcodes', trx_addons_js_composer_init);
	jQuery(document).on('action.init_hidden_elements', trx_addons_js_composer_init);
	
	function trx_addons_js_composer_init(e, container) {
		if (container === undefined) container = jQuery('body');
		if (container.length === undefined || container.length == 0) return;
		
		// Close button in the messagebox
		container.find('.vc_message_box_closeable:not(.inited)').addClass('inited').on('click', function(e) {
			jQuery(this).fadeOut();
			e.preventDefault();
			return false;
		});
	}
	
	
	// Fix columns
	jQuery(document).on('action.resize_trx_addons', trx_addons_js_composer_fix_column);
	jQuery(document).on('action.scroll_trx_addons', trx_addons_js_composer_fix_column);
	
	function trx_addons_js_composer_fix_column(e, cont) {
		if (cont===undefined) cont = jQuery('body');
		cont.find('.vc_column_fixed').each(function() {
			var col = jQuery(this),
				row = col.parent();
			
			// Exit if non-standard responsive is used for this columns
			if (col.attr('class').indexOf('vc_col-lg-')!=-1 || col.attr('class').indexOf('vc_col-md-')!=-1) {
				return;

			// Unfix when sidebar is under content
			} else if (jQuery(window).width() < 768) {
				var old_style = col.data('old_style');
				if (old_style !== undefined) col.attr('style', old_style).removeAttr('data-old_style');
		
			} else {
		
				var col_height = col.outerHeight();
				var row_height = row.outerHeight();
				var row_top = row.offset().top;
				var scroll_offset = jQuery(window).scrollTop();
				var top_panel_fixed_height = trx_addons_fixed_rows_height();
	
				// If sidebar shorter then content and page scrolled below the content's top
				if (col_height < row_height && scroll_offset + top_panel_fixed_height > row_top) {
					
					var col_init = {
							'position': 'undefined',
							'top': 'auto',
							'bottom' : 'auto'
							};
					
					if (typeof TRX_ADDONS_STORAGE['scroll_offset_last'] == 'undefined') {
						TRX_ADDONS_STORAGE['col_top_last'] = row_top;
						TRX_ADDONS_STORAGE['scroll_offset_last'] = scroll_offset;
						TRX_ADDONS_STORAGE['scroll_dir_last'] = 1;
					}
					var scroll_dir = scroll_offset - TRX_ADDONS_STORAGE['scroll_offset_last'];
					if (scroll_dir == 0)
						scroll_dir = TRX_ADDONS_STORAGE['scroll_dir_last'];
					else
						scroll_dir = scroll_dir > 0 ? 1 : -1;
					
					var col_big = col_height + 30 >= jQuery(window).height() - top_panel_fixed_height,
						col_top = col.offset().top;

					if (col_top < 0) col_top = TRX_ADDONS_STORAGE['col_top_last'];


					// If column height greater then window height
					if (col_big) {
	
						// If change scrolling dir
						if (scroll_dir != TRX_ADDONS_STORAGE['scroll_dir_last'] && col.css('position') == 'fixed') {
							col_init.top = col_top - row_top;
							col_init.position = 'absolute';
	
						// If scrolling down
						} else if (scroll_dir > 0) {
							if (scroll_offset + jQuery(window).height() >= row_top + row_height + 30) {
								col_init.bottom = 0;
								col_init.position = 'absolute';
							} else if (scroll_offset + jQuery(window).height() >= (col.css('position') == 'absolute' ? col_top : row_top) + col_height + 30) {
								
								col_init.bottom = 30;
								col_init.position = 'fixed';
							}
					
						// If scrolling up
						} else {
							if (scroll_offset + top_panel_fixed_height <= col_top) {
								col_init.top = top_panel_fixed_height;
								col_init.position = 'fixed';
							}
						}
					
					// If column height less then window height
					} else {
						if (scroll_offset + top_panel_fixed_height >= row_top + row_height - col_height) {
							col_init.bottom = 0;
							col_init.position = 'absolute';
						} else {
							col_init.top = top_panel_fixed_height;
							col_init.position = 'fixed';
						}
					}
					
					if (col_init.position != 'undefined') {
						// Insert placeholder after this column
						if (!col.prev().hasClass('trx_addons_fixed_column_placeholder')) {
							col.before('<div class="trx_addons_fixed_column_placeholder '+col.attr('class')+'"></div>');
							col.prev().removeClass('vc_column_fixed');
						}
						// Detect horizontal position when resize
						col_init.left = col_init.position == 'fixed' ? col.prev().offset().left : col.prev().position().left;
						col_init.width = col.prev().width();
						// Set position
						if (col.css('position') != col_init.position 
							|| TRX_ADDONS_STORAGE['scroll_dir_last'] != scroll_dir
							|| col.width() != col_init.width) {
							if (col.data('old_style') === undefined) {
								var style = col.attr('style');
								if (!style) style = '';
								col.attr('data-old_style', style);
							}
							col.css(col_init);
						}
					}

					TRX_ADDONS_STORAGE['col_top_last'] = col_top;
					TRX_ADDONS_STORAGE['scroll_offset_last'] = scroll_offset;
					TRX_ADDONS_STORAGE['scroll_dir_last'] = scroll_dir;
	
				} else {
	
					// Unfix when page scrolling to top
					var old_style = col.data('old_style');
					if (old_style !== undefined) {
						col.attr('style', old_style).removeAttr('data-old_style');
						if (col.prev().hasClass('trx_addons_fixed_column_placeholder'))
							col.prev().remove();
					}

				}
			}
		});
	}
	
})();