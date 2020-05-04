<?php
/**
 * @package Watson
 */
/*
Template Name: With Sidebar
*/
?>
<?php get_header(); ?>
<div role="main">
	<div class="content entry-page">
		<div class="inner-content">
		<?php while( have_posts() ) : the_post(); ?>
			<div id="page-<?php the_ID(); ?>">
					<?php get_template_part( '_post-header' ); ?>
					<?php the_content( __( 'Read article', 'watson' ) ); ?>
					<?php edit_post_link( __( 'Edit page', 'watson' ), '<p class="clear">', '</p>' ); ?>
			</div>
			<?php comments_template( '', true ); ?>
		<?php endwhile; ?>
		</div>
	</div>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>