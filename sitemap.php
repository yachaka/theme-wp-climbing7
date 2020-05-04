<?php
/**
 * @package Watson
 */
/*
Template Name: Sitemap
*/
?>
<?php get_header(); ?>
<?php while( have_posts() ) : the_post(); ?>
<div role="main">
	<div class="content entry-page">
		<div class="inner-content tmp-sitemap">
			<?php get_template_part( '_post-header' ); ?>
			<?php the_content( '' ); ?>
			<section>
				<h2><?php _e( 'Pages', 'watson' ); ?></h2>
				<ul>
					<?php wp_list_pages( 'sort_column=menu_order&depth=0&title_li=' ); ?>
				</ul>
				<h2><?php _e( 'Recent posts', 'watson' ); ?></h2>
				<ul>
					<?php $recent_posts = get_posts( array( 'numberposts' => 25 ) ); ?>
					<?php if ( ! empty( $recent_posts ) ) : ?>
							<ul>
								<?php foreach( $recent_posts as $post ) : setup_postdata( $post ); ?>
									<li>
										<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
											<?php echo get_the_title() ? get_the_title() : 'Untitled'; ?>
										</a>
										<time datetime="<?php the_time( 'Y-M-D\Th:m:sT' ); ?>" pubdate="pubdate">
											/ <?php the_time( get_option( 'date_format' ) ); ?>
										</time>
									</li>
								<?php endforeach; ?>
							</ul>
							<?php wp_reset_postdata(); ?>
					<?php endif; ?>
				</ul>
				<h2><?php _e( 'Authors', 'watson' ); ?></h2>
				<ul>
					<?php wp_list_authors(); ?>
				</ul>
				<h2><?php _e( 'Categories', 'watson' ); ?></h2>
				<ul>
					<?php wp_list_categories( 'depth=0&title_li=&show_count=1' ); ?>
				</ul>
				<h2><?php _e( 'Archives', 'watson' ); ?></h2>
				<ul>
					<?php wp_get_archives( 'type=monthly' ); ?>
				</ul>
				<?php wp_reset_query(); rewind_posts(); the_post(); ?>
				<?php edit_post_link( __( 'Edit page', 'watson' ), '<p class="clear">', '</p>' ); ?>
			</section>
			<?php comments_template( '', true ); ?>
		</div>
	</div>
	<?php get_sidebar(); ?>
</div>
<?php endwhile; ?>
<?php get_footer(); ?>