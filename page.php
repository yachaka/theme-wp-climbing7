<?php
/**
 * @package Watson
 */
?>
<?php get_header(); ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<div id="page-<?php the_ID(); ?>">
			<div class="entry page-wrapper">
				<?php get_template_part( '_post-header' ); ?>
				<?php if ( ! post_password_required() ) : ?>
					<?php get_template_part( '_featured' ); ?>
				<?php endif; ?>
					<section style="margin-top:40px;column-count:3;column-gap:30px;"
						<?php the_content( __( 'Read article', 'watson' ) ); ?>
					</section>
				<?php edit_post_link( __( 'Edit page', 'watson' ), '<p class="clear">', '</p>' ); ?>
				<?php wp_link_pages( 'before=<div class="page-links">' . __( 'Page:', 'watson' ) . '&after=</div>' ); ?>
				<?php comments_template( '', true ); ?>
			</div>
		</div>
	<?php endwhile; ?>
<?php get_footer(); ?>