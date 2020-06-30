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

        <p style="font-size:0.7rem;font-weight:100;width:75px;border-radius:15px;text-align:center;margin:auto;background-color: #DE3163;color:#fff;letter-spacing: 0.1rem;margin-top:20px;margin-bottom:20px;padding:2px 0 1px 0;">VOYAGE</p>
        
        <?php get_template_part( '_post-header' ); ?>
        
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

