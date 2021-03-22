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
						<div id="home_infos-featured">


							<ul class="categorie_tag_featured_home">
								<li class="categorie-tag_featured">
									<a class="pastille_featured_slider" href="
									

									<?php
									//recuperer l'url de la page archive de l'activite du dernier post
									$url_site = get_site_url ();
									echo $url_site;?>
									/activites/
									
									<?php

									$activites = get_the_terms (get_the_ID(),'activites');
									echo ($activites[0]->slug);
									?>
									">


									<?php

									$activites = get_the_terms(get_the_ID(),'activites');
									// méthode 1 par étape qui liste toutes les categories
									// foreach ($categories as $categorie) {
									// 	echo ($categorie->name);
									// }
									// méthode 2 avec acces direct mais si pas d'entrée => crash php
									echo ($activites[0]->name);
									?>
									</a>
								</li>
								
								<?php
								$lieux = recuperationPaysRegions(get_the_ID());
								$payss = $lieux[0];
								$regions = $lieux[1];
								?>

								<li class="categorie-tag_featured">
									<?php
									affichageLiensLieux($payss[0]);
									?>
								</li>

								<?php if (count($regions) > 0) : ?>

								<li class="categorie-tag_featured">
									<?php
									affichageLiensLieux($regions[0]);
									?>
								</li>

								<?php endif; ?>

							</ul>
								
							<a title="<?php esc_attr_e( 'Read full article', 'watson' ); ?>" href="<?php the_permalink(); ?>">
							<h1 class="home_titre-featured" style="color:#FFF;"><?php the_title(); ?></h1>
							</a>
							<?php 
							// echo ttf_common_get_truncated_excerpt( 180 ); 
							?>
							<p  class="home_date-titre-featured" >Topo publié le <?php the_date (); ?></p>
						</div>

					</section>
				</li>
			<?php endwhile; ?>
		</ul>
		<ul class="rslides-direction-nav"></ul>
	</div>
	<?php wp_reset_postdata(); ?>
<?php endif; ?>
