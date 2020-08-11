<?php
/**
 * @package Watson
 */
?>
<?php if ( '' != get_the_post_thumbnail() ) : ?>
	<figure class="feature">
		<?php 
		the_post_thumbnail( 'watson_featured' ); 
		?>
		<?php 
		// the_post_thumbnail(); 
		?>
		<?php watson_post_thumbnail_caption(); ?>
	</figure>
<?php endif; ?>