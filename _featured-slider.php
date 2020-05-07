<?php
/**
 * @package Watson
 */
?>
<?php $featured_posts = ttf_get_featured_slider_query(); ?>
<?php if ( $featured_posts->have_posts() ) : ?>
	<?php $autostart_enabled = watson_option( 'autostart_slider' ); ?>
	<div class="responsive-slides"<?php echo $autostart_enabled ? ' data-autostart="true"' : ''; ?>>


		<ul class="slides">
			<?php while( $featured_posts->have_posts() ) : $featured_posts->the_post(); ?>
				<li>
					<section style="position:relative;" class="featured-article">
							<a title="<?php esc_attr_e( 'Read full article', 'watson' ); ?>" href="<?php the_permalink(); ?>">
							<?php 
							// the_post_thumbnail( 'watson_featured' ); désactivé le redimensionnement de la fonction
							?>
							<?php the_post_thumbnail(); ?>
							</a>
						<div style="position:absolute;bottom:0px;left:0px;right:0;padding:80px 30px 30px 50px;background: rgb(0,0,0);background: linear-gradient(0deg, rgba(0,0,0,.6) 50%, rgba(255,255,255,0) 100%);">
							

							<ul class="categorie_tag_featured_home">
								<li style="background-color: #FF7F00;" class="categorie-tag_featured">
									<?php
									$activites = get_the_terms(get_the_ID(),'activity');
									// méthode 1 par étape qui liste toutes les categories
									// foreach ($categories as $categorie) {
									// 	echo ($categorie->name);
									// }
									// méthode 2 avec acces direct mais si pas d'entrée => crash php
									echo ($activites[0]->name);
									?>
								</li>
								
								<?php
								// Récupération des lieux
								$lieux = get_the_terms (get_the_ID(), 'region');

								// Filtre des lieux pour garder les lieux SANS lieu parent,
								// Donc les pays
								$payss = array_filter($lieux, function ($lieu) {
									return $lieu->parent === 0;
								});
								$payss = array_values($payss); // pour ré-ordonner l'array à partir de 0

								// Filtre des lieux pour garder les lieux AVEC lieu parent,
								// Donc les régions
								$regions = array_filter($lieux, function ($lieu) {
									return $lieu->parent !== 0;
								});

								// Création d'une liste avec les noms des régions
								$regionsNames = [];
								// autre manière : $regionsNames = array();

								foreach ($regions as $pos => $region) {
									$regionsNames[] = $region->name; // ajoute un element a la fin de la liste
								}

								// Autre manière de faire une liste de noms des régions :

								// $regionsNames = array_map(
								// 	function ($region) {
								// 		return $region->name;
								// 	},
								// 	$regions
								// );

								?>

								<li style="background-color:#007FFF;" class="categorie-tag_featured">
									<?php
										echo $payss[0]->name;
									?>
								</li>

								<li style="background-color:#007FFF;" class="categorie-tag_featured">
									<?php
										echo join(' , ', $regionsNames);
									?>
								</li>
							</ul>
								
							<a title="<?php esc_attr_e( 'Read full article', 'watson' ); ?>" href="<?php the_permalink(); ?>">
							<h1 style="color:#FEFEE2;"><?php the_title(); ?></h1>
							</a>
							<?php 
							// echo ttf_common_get_truncated_excerpt( 180 ); 
							?>
							<p  style="color:#FEFEE2;font-size:0.9rem;font-family: 'Source Sans Pro', Helvetica, Arial, Verdana, Tahoma, sans-serif;">Publié le <?php the_date (); ?></p>
						</div>

					</section>
				</li>
			<?php endwhile; ?>
		</ul>
		<ul class="rslides-direction-nav"></ul>
	</div>
	<?php wp_reset_postdata(); ?>
<?php endif; ?>
