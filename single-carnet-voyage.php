<?php
/**
 * @package Watson
 */
?>
<?php get_header(); ?>

<div class="wrapper">

  <?php while ( have_posts() ) : the_post(); ?>

    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

      <div class="entry">
        
        <?php get_template_part( '_post-header' ); ?>
        <?php get_template_part( '_featured' ); ?>
        
        <div class="contenu">
          <?= the_content() ?>
        </div>

        <div class="clear">
          <?php wp_link_pages( 'before=<p class="page-links">Page:&after=</p>' ); ?>
        </div>
        
        <?php edit_post_link( __( 'Edit post', 'watson' ), '<p class="clear">', '</p>' ); ?>
        <?php get_template_part( '_post-footer' ); ?>
        <?php comments_template( '', true ); ?>

      
      </div>
    
    </div>

  <?php endwhile; ?>

<?php get_footer(); ?>  
  
</div>

