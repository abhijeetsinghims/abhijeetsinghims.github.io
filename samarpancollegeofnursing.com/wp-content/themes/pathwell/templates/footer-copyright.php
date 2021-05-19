<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0.10
 */

// Copyright area
?> 
<div class="footer_copyright_wrap<?php
				if (!pathwell_is_inherit(pathwell_get_theme_option('copyright_scheme')))
					echo ' scheme_' . esc_attr(pathwell_get_theme_option('copyright_scheme'));
 				?>">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text"><?php
				// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
				$pathwell_copyright = pathwell_prepare_macros(pathwell_get_theme_option('copyright'));
				if (!empty($pathwell_copyright)) {
					// Replace {date_format} on the current date in the specified format
					if (preg_match("/(\\{[\\w\\d\\\\\\-\\:]*\\})/", $pathwell_copyright, $pathwell_matches)) {
						$pathwell_copyright = str_replace($pathwell_matches[1], date_i18n(str_replace(array('{', '}'), '', $pathwell_matches[1])), $pathwell_copyright);
					}
					// Display copyright
					echo wp_kses_data(nl2br($pathwell_copyright));
				}
			?></div>
		</div>
	</div>
</div>
