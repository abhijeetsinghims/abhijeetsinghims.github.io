<?php
/**
 * The template to display the background video in the header
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0.14
 */
$pathwell_header_video = pathwell_get_header_video();
$pathwell_embed_video = '';
if (!empty($pathwell_header_video) && !pathwell_is_from_uploads($pathwell_header_video)) {
	if (pathwell_is_youtube_url($pathwell_header_video) && preg_match('/[=\/]([^=\/]*)$/', $pathwell_header_video, $matches) && !empty($matches[1])) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr($matches[1]); ?>"></div><?php
	} else {
		global $wp_embed;
		if (false && is_object($wp_embed)) {
			$pathwell_embed_video = do_shortcode($wp_embed->run_shortcode( '[embed]' . trim($pathwell_header_video) . '[/embed]' ));
			$pathwell_embed_video = pathwell_make_video_autoplay($pathwell_embed_video);
		} else {
			$pathwell_header_video = str_replace('/watch?v=', '/embed/', $pathwell_header_video);
			$pathwell_header_video = pathwell_add_to_url($pathwell_header_video, array(
				'feature' => 'oembed',
				'controls' => 0,
				'autoplay' => 1,
				'showinfo' => 0,
				'modestbranding' => 1,
				'wmode' => 'transparent',
				'enablejsapi' => 1,
				'origin' => home_url(),
				'widgetid' => 1
			));
			$pathwell_embed_video = '<iframe src="' . esc_url($pathwell_header_video) . '" width="1170" height="658" allowfullscreen="0" frameborder="0"></iframe>';
		}
		?><div id="background_video"><?php pathwell_show_layout($pathwell_embed_video); ?></div><?php
	}
}
?>