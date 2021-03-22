<?php
/**
 * @package Watson
 */
?>
<?php //$featured_thumbnails = watson_get_featured_thumbnails_query(); ?>

<?php
$derniers_posts_de_voyage = new WP_Query(array(
	'post_type' => ['album-voyage', 'carnet-voyage'],
	'posts_per_page' => 4,
));

if ( $derniers_posts_de_voyage->have_posts() ) : ?>
	<div class="featured-thumbnails-container">
		<h3 style="margin-top:0;">Derniers posts de voyage</h3>
		<div id="featured-thumbnails-mobile">
		<?php $featured_thumbnail_counter = 0; ?>
		<?php while ( $derniers_posts_de_voyage->have_posts() ) : $derniers_posts_de_voyage->the_post(); ?>
			<a href="<?php the_permalink(); ?>" title="Lire en entier" rel="bookmark">
				<div class="featured-thumbnail<?php echo ( 3 == $featured_thumbnail_counter ) ? ' last' : ''; ?>">
					<?php the_post_thumbnail( '' ); ?>
					<p><?php the_title(); ?></p>
				</div>
			</a>
			<?php $featured_thumbnail_counter++; ?>
		<?php endwhile; ?>
	</div>
	</div>
	<?php wp_reset_postdata(); ?>
<?php endif; ?>