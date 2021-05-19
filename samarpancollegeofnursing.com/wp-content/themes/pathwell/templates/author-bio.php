<?php
/**
 * The template to display the Author bio
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */
?>

<div class="author_info scheme_dark author vcard" itemprop="author" itemscope itemtype="http://schema.org/Person">

	<div class="author_avatar" itemprop="image">
		<?php 
		$pathwell_mult = pathwell_get_retina_multiplier();
		echo get_avatar( get_the_author_meta( 'user_email' ), 120*$pathwell_mult ); 
		?>
	</div><!-- .author_avatar -->

	<div class="author_description">
		<h6 class="author_title" itemprop="name"><?php
			// Translators: Add the author's name in the <span> 
			echo wp_kses_data(sprintf(__('About %s', 'pathwell'), '<span class="fn">'.get_the_author().'</span>'));
		?></h6>

		<div class="author_bio" itemprop="description">
			<?php echo wp_kses_post(wpautop(get_the_author_meta( 'description' ))); ?>			
			<?php do_action('pathwell_action_user_meta'); ?>
		</div><!-- .author_bio -->

	</div><!-- .author_description -->

</div><!-- .author_info -->
