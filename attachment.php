<?php
/**
 * @package Watson
 */
?>
<?php get_header(); ?>
<?php while( have_posts() ) : the_post(); ?>
<div class="tmp-attachment">
	<?php
		if ( wp_attachment_is_image ( $post->ID ) ) {
			$img_src = wp_get_attachment_image_src( $post->ID, 'large' );
			$alt_text = get_post_meta( $post->ID, '_wp_attachment_image_alt', true );
	?>
		<figure>
			<?php if ( $parent_id = wp_get_post_parent_id( get_the_ID() ) ) : ?>
				<a href="<?php echo esc_url( get_permalink( $parent_id ) ); ?>"><?php printf( __( 'Return to %s', 'watson' ), '<em>' . get_the_title( $parent_id ) . '</em>' ); ?></a>
		<?php endif; ?>
			<img src="<?php echo esc_url( $img_src[0] ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>">
		</figure>
	<?php
		} else {
			echo basename( wp_get_attachment_url( $post->ID ) );
		}
	?>
	<figcaption>
		<h1><?php the_title(); ?></h1>
		<section>
			<span>
				<?php the_excerpt(); ?>
			</span>
		</section>
	</figcaption>
	<nav class="attach-nav clear">
		<p class="prev alignleft"><?php previous_image_link( 0, __( 'Previous', 'watson' ) ); ?></p>
		<p class="next alignright"><?php next_image_link( 0, __( 'Next', 'watson' ) ); ?></p>
	</nav>
</div>
<?php endwhile; ?>
<?php get_footer(); ?>