<?php
/**
 * @package Watson
 */
?>
<?php get_header(); ?>
<div role="main">
	<h2 class="subheading"><?php printf( __( "Search results for &#8216;%s&#8217;", "watson" ), get_search_query() ); ?></h2>
	<div class="content<?php if ( ! is_active_sidebar( 'primary_sidebar' ) ) { echo " no-sidebar"; } ?>">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="inner-content">
						<?php get_template_part( '_post-header' ); ?>
						<?php the_excerpt(); ?>
						<a class="custom-more-link" title="<?php esc_attr_e( 'Read full article', 'watson' ); ?>" href="<?php the_permalink(); ?>#more-<?php echo $post->ID; ?>"><?php _e( 'Read article', 'watson' ); ?></a>
					</div>
				</div>
			<?php endwhile; ?>
			<nav class="post-footer index-footer">
				<p>
					<?php next_posts_link( __( 'Older posts', 'watson' ) ); ?>
					<?php previous_posts_link( __( 'Newer posts', 'watson' ) ); ?>
				</p>
			</nav>
			<?php else : ?>
				<div>
					<p><?php printf( __( 'Sorry, your search for &#8216%s&#8217 did not turn up any results. Please try again.', 'watson' ), get_search_query());?></p>
					<?php get_search_form(); ?>
				</div>
		<?php endif; ?>
	</div>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>