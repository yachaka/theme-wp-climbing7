<?php
/**
 * @package Watson
 */
?>
<footer class="post-footer">
	<section>
		<?php if ( ! watson_option( 'hide_categories' ) ) : ?>
			<?php if  ( has_category() ) : ?>
				<div class="cat-links">
					<?php _e( 'Categories: ', 'watson' ); ?>
					<em><?php the_category( __( ', ', 'watson' ) ); ?></em>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php if ( ! watson_option( 'hide_tags' ) ) : ?>
			<?php if  ( has_tag() ) : ?>
				<div class="tag-links">
					<?php _e( 'Tagged: ', 'watson' ); ?>
					<?php the_tags( '<em>', __( ', ', 'watson' ),'</em>' ); ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</section>
	<?php if ( ! watson_option( 'hide_post_nav' ) ) : ?>
		<nav>
			<?php if ( get_adjacent_post( false, '', true ) || get_adjacent_post( false, '', false ) ) : ?>
				<p title="<?php esc_attr_e( 'Read next article', 'watson' ); ?>"><?php next_post_link( '%link', '%title' ); ?></p>
				<p title="<?php esc_attr_e( 'Read previous article', 'watson' ); ?>"><?php previous_post_link( '%link', '%title' ) ?></p>
			<?php endif; ?>
		</nav>
	<?php endif; ?>
</footer>