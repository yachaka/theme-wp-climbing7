<?php
/**
 * @package Watson
 */
/*
Template Name: Full-width
*/
?>
<?php get_header(); ?>
<div role="main">
	<?php while( have_posts() ) : the_post(); ?>
		<div id="page-<?php the_ID(); ?>" class="entry-page">
			<?php get_template_part( '_post-header' ); ?>
			<?php the_content( __( 'Read article', 'watson' )); ?>
			<?php edit_post_link( __( 'Edit page', 'watson' ), '<p class="clear">', '</p>' ); ?>
		</div>
		<?php comments_template( '', true ); ?>
	<?php endwhile; ?>
</div>
<?php get_footer(); ?>