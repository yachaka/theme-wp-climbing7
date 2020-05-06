<?php
/**
 * @package Watson
 */
?>
<?php $featured_thumbnails = watson_get_featured_thumbnails_query(); ?>
<?php if ( $featured_thumbnails->have_posts() ) : ?>
	<div class="featured-thumbnails-container">
		<h4 style="margin-top:0;">Derniers posts de voyage</h4>
		<?php $featured_thumbnail_counter = 0; ?>
		<?php while ( $featured_thumbnails->have_posts() ) : $featured_thumbnails->the_post(); ?>
			<a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'Read full article', 'watson' ); ?>" rel="bookmark">
				<div class="featured-thumbnail<?php echo ( 3 == $featured_thumbnail_counter ) ? ' last' : ''; ?>">
					<?php the_post_thumbnail( 'watson_featured_thumbnail' ); ?>
					<p><?php the_title(); ?></p>
				</div>
			</a>
			<?php $featured_thumbnail_counter++; ?>
		<?php endwhile; ?>
	</div>
	<?php wp_reset_postdata(); ?>
<?php endif; ?>