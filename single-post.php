<?php
/**
 * @package Watson
 */

// Ajout de 'archive.css'
wp_enqueue_style(
	'single-post',
	get_stylesheet_directory_uri() . '/single-post.css',
	array(),
	filemtime(get_template_directory() . '/single-post.css'),
	false
);
?>
<?php 
 get_header(); ?>

<div class="wrapper">
	<?php while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<div class="entry">

				<!-- <p class="single-post__pilule">
					TOPO <span style="text-transform:uppercase;">
						<?php 
						$activites = get_the_terms(get_the_ID(), 'activites');

						if (count($activites) > 0) { // Si la liste contient des éléments
							$activite_numero_1 = $activites[0]; // Premier élement
							// echo $activite_numero_1->name;
						}
						echo $activite_numero_1->name; ?></span>
				</p> -->
				
				
				
				<div class="single-post__bloc-post">
					<?php if ( ! watson_option( 'hide_post_featured' ) && ! post_password_required() ) : ?>
					<?php get_template_part( '_post-header' ); ?>
					<?php get_template_part( '_featured' ); ?>
					<?php endif; ?>					
					<br/>
					<div class="single-post_presentation">
						<?php the_field( 'presentation' ); ?>
					</div>
					<hr/>
					<?php
					$fiche_technique = get_field('fiche_technique');
					$liens = get_field('liens');

					if ($fiche_technique || $liens):
					?>

					<aside>
						
						<?php
						if ($fiche_technique):
						?>
							<div class="single-post__fiche-technique single-post__mobile">
								<h3>Fiche technique</h3>
								<p><?php the_field( 'fiche_technique' ); ?></p>
							</div>
						<?php endif; ?>

						<?php
						if ($liens):
						?>
							<div class="single-post__liens single-post__mobile">
								<h3 >Ressources</h3>
								<?php the_field('liens'); ?>

							</div>

						<?php endif; ?>
						
						<?php 

						/* Ton précédent code : */

						// $activite = get_the_terms(get_the_ID(), 'activites');

						// if (isset ($activite["name"])){
						// 	echo $activite["name"];
						// }

						/* Pas fonctionnel, car $activite est une liste.
						 * Pour y accéder, précises l'entrée [0].
						 * De plus, utilises ->name et non ['name'] (ici, car c'est un "object")
						 */

						/* Code correct */
						$activites = get_the_terms(get_the_ID(), 'activites');

						if (count($activites) > 0) { // Si la liste contient des éléments
							$activite_numero_1 = $activites[0]; // Premier élement
							// echo $activite_numero_1->name;
						}

						?>
							<div class="single-post__fiche-activite">
								<a href="
									<?php
										if ($activite_numero_1->name == 'Canyoning') {
											echo 'http://climbing7.com/a-propos-du-canyoning/';
										}
										elseif ($activite_numero_1->name == 'Escalade') {
											echo 'http://climbing7.com/a-propos-de-lescalade/';
										}
										elseif ($activite_numero_1->name == 'Via ferrata') {
											echo 'http://climbing7.com/a-propos-des-via-ferrata/';
										}
										elseif ($activite_numero_1->name == 'Ski de rando') {
											echo 'http://climbing7.com/a-propos-du-ski-de-rando/';
										}
										elseif ($activite_numero_1->name == 'Trekking') {
											echo 'http://climbing7.com/a-propos-du-trekking/';
										}
									?>
								">COTATIONS <span style="text-transform:uppercase;"><?php echo $activite_numero_1->name; ?></span></a>
							</div>

					</aside>

					<?php
					endif;
					?>

					

				</div>

				<div class="single-post__bloc-post single-post__mobile">
					<h3>Accès au site</h3>
					<?php the_field( 'acces_au_site' ); ?>
					
				</div>

				<div class="single-post__bloc-post single-post__mobile">

					<h3>Carte & Topo</h3>

					<?= do_shortcode( get_field( 'texte_carte' ) ) ?>
					<?= do_shortcode( get_field( 'carte_iframe' ) ); ?>
					<div class="topo_img">
						<?php
						$images_topo = [
							get_field('carte_image_1'),
							get_field('carte_image_2'),
							get_field('carte_image_3'),
							get_field('carte_image_4'),
							get_field('carte_image_5'),
						];

						foreach ($images_topo as $image):
							if ($image === null || $image === false) {
								continue ;
							}

							$image_url = wp_get_attachment_image_src( $image, 'full' )[0]; // On récupère l'URL
						?>

							<a href="<?= $image_url ?>">
								<?= wp_get_attachment_image($image, 'full' ) ?>
							</a>
						<?php endforeach; ?>
					</div>

				</div>

				<div class="single-post__bloc-post single-post__mobile">
					<h3>Description du parcours</h3>
					
					<div class="single-post__parcours">
						<?php
						$approche = get_field( 'approche' );
						if ($approche):
						?>

							<h4>Approche <?php the_field( 'duree_approche' ); ?></h4>
							<?= $approche ?>

						<?php endif; ?>

						<?php
						$parcours = get_field( 'parcours' );
						if ($parcours):
						?>
							<h4>Parcours <?php the_field( 'duree_parcours' ); ?></h4> 
							<?= $parcours ?>
						<?php endif; ?>

						<?php
						$retour = get_field( 'retour' );
						if ($retour):
						?>
							<h4>Retour <?php the_field( 'duree_retour' ); ?></h4> 
							<?= $retour ?>
						<?php endif; ?>

					</div>
				</div>

				<?php
				$galerie = get_field('galerie');

				if ($galerie):
				?>
					<div class="single-post__bloc-post single-post__mobile">
						<h3>En images</h3>
						<?= 
						// do_shortcode($galerie);
						the_field('galerie');
						?>
					</div>

				<?php endif; ?>

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


