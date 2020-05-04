<?php
/**
 * @package Watson
 */
?>
<?php get_header(); ?>

<div class="wrapper">

	<?php while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>

			<div class="entry">

				<p style="font-size:0.7rem;font-weight:100;width:75px;border-radius:15px;text-align:center;margin:auto;background-color: #DE3163;color:#fff;letter-spacing: 0.1rem;margin-top:20px;margin-bottom:20px;padding:2px 0 1px 0;">TOPO</p>
				
				<?php get_template_part( '_post-header' ); ?>
				
				<div class="bloc_post" style="padding-bottom:30px;border-bottom: 0.5px solid #ddd;margin-bottom:20px;">
					
					<aside style="width:31%; float:right;">
						
						<div style="padding-bottom:20px;border-bottom: 0.5px solid #ddd;margin-bottom:20px;">
							<h3 style="margin-top:0;">Fiche technique</h3>
							<?php the_field( 'fiche_technique' ); ?>
						</div>
						

						<h3 style="margin-top:0;">Ressources</h3>
						<?php the_field( 'liens' ); ?>

					</aside>


					<div style="width:64%;">
						<?php if ( ! watson_option( 'hide_post_featured' ) && ! post_password_required() ) : ?>
						<?php get_template_part( '_featured' ); ?>
						<?php endif; ?>
						<span style="font-weight:600;"><?php the_field( 'presentation' ); ?></span>
					</div>

				</div>

				<div class="bloc_post" style="padding-bottom:30px;border-bottom: 0.5px solid #ddd;margin-bottom:20px;">
					<h3>Accès au site</h3>
					<?php the_field( 'acces_au_site' ); ?>
					<?php the_field( 'carte_iframe' ); ?>
				</div>





				<div class="bloc_post" style="padding-bottom:30px;border-bottom: 0.5px solid #ddd;margin-bottom:20px;">
					<h3>Carte & Topo</h3>
						<div class="topo_img">
							<?php 
							$image_id = get_field( 'carte_image_1' ); // On récupère l'ID
							$image_url = wp_get_attachment_image_src( $image_id, 'full' )[0]; // On récupère l'URL
							?>

							<a href="<?php echo $image_url; ?>">
								<?php
								if( $image_id ) {	
									echo wp_get_attachment_image($image_id, 'full' );
    							}
								?>
							</a>

							<?php 
							$image_id = get_field( 'carte_image_2' ); // On récupère cette fois l'ID
							$image_url = wp_get_attachment_image_src( $image_id, 'full' )[0]; // On récupère l'URL
							?>
							<a href="<?php echo $image_url; ?>">
								<?php
								if( $image_id ) {	
								echo wp_get_attachment_image( $image_id, 'full' );
    							}
							?>
							</a>

							<?php 
							$image_id = get_field( 'carte_image_3' ); // On récupère l'ID
							$image_url = wp_get_attachment_image_src( $image_id, 'full' )[0]; // On récupère l'URL
							?>
							<a href="<?php echo $image_url; ?>">
								<?php
								if( $image_id ) {	
								echo wp_get_attachment_image( $image_id, 'full' );
    							}
							?>
							</a>
							
						</div>
				</div>

				<div class="bloc_post" style="padding-bottom:30px;border-bottom: 0.5px solid #ddd;margin-bottom:20px;">
					<h3>Description du parcours</h3>
						<div style="column-count: 3;column-gap:30px;margin-top:30px;">
							<h4 style="margin-top:0;">Approche <?php the_field( 'duree_approche' ); ?></h4>
							<?php the_field( 'approche' ); ?>
							<h4>Parcours <?php the_field( 'duree_parcours' ); ?></h4> 
							<?php the_field( 'parcours' ); ?>				
						</div>
				</div>

				<div class="bloc_post" style="padding-bottom:30px;border-bottom: 0.5px solid #ddd;margin-bottom:20px;">
					<h3>En images</h3>
					<?php the_content( __( 'Read article', 'watson' )); ?>
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

