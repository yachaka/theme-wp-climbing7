<?php
/**
 * @package Watson
 */

// Ajout de 'archive.css'
wp_enqueue_style(
	'archive',
	get_stylesheet_directory_uri() . '/archive.css',
	array(),
	filemtime(get_template_directory() . '/archive.css'),
	false
);

/*
 * Filtres supplémentaires 'activités' ou 'lieux'
 */
$est_page_archive_lieu = is_tax('lieux');
$est_page_archive_activite = is_tax('activites');
$est_page_archive_taxonomie = is_tax();

$nom_taxonomie_filtre = null;
$filtres_taxonomie = null;

// Si on est sur une page archive d'un lieu ou d'une activité,
// On veut montrer les filtres, inversement, des lieux ou des activités
if ($est_page_archive_taxonomie) {
	$taxonomie_actuelle = get_queried_object();

	if ($taxonomie_actuelle->taxonomy === 'lieux') {
		$nom_taxonomie_filtre = 'activites';
	} else if ($taxonomie_actuelle->taxonomy === 'activites') {
		$nom_taxonomie_filtre = 'lieux';
	} else {
		// Autre taxonomie que Lieu ou Activité.
		// On choisit de ne pas afficher de filtres
	}

	if ($nom_taxonomie_filtre !== null) {
		$tous_les_posts_ID_taxonomie_actuelle = (
			new WP_Query(array(
				'nopaging' => true,
				'fields' => 'ids',
				'tax_query' => array(
					array(
						'taxonomy' => $taxonomie_actuelle->taxonomy,
						'field' => 'slug',
						'terms' => $taxonomie_actuelle->slug,
					)
				)
			))
		)->get_posts();

		$filtres_taxonomie = get_terms(array(
			'taxonomy' => $nom_taxonomie_filtre,
			'parent'  => 0, //pour limiter aux parents seulement
			'object_ids' => $tous_les_posts_ID_taxonomie_actuelle,
		));
	}
}

// Début du HTML
?>
<?php get_header(); ?>
<div id="page_archive" role="main">

	<p id="pastille">
		<?php
		$mot = 'TOPOS';

		if (is_post_type_archive('album-voyage')
			|| is_post_type_archive('carnet-voyage')) {
			$mot = 'VOYAGES';
		}
		
		echo $mot;
		?>
	</p>

	<h3 id="titre" class="subheading">
		<?php
			ttf_common_archives_title();

			if (is_paged()) {
			?>
				<span class="numero-page">
					page <?= get_query_var('paged') ?>
				</span>
			<?php
			}
		?>

	</h3>

	<?php if ($filtres_taxonomie !== null): ?>
		<p id="filtrer_par_texte">
			<?php
			if ($est_page_archive_lieu) {
				echo 'Filtrer par activités';
			} else {
				echo 'Filtrer par lieux';
			}
			?>
		</p>

		<ul class="liste_filtres">
			

			

			<?php 

			$nom_du_filtre = null;
				if ($est_page_archive_activite) {
					$nom_du_filtre = 'f_lieux';
				}
				else {
					$nom_du_filtre ='f_activites';
				}
			
			// définit la variable qui contient l'url sans les ?f_lieux
			$url = get_site_url() . '/' . $taxonomie_actuelle->taxonomy . '/' . $taxonomie_actuelle->slug;

			echo '<li class="filtre"><a href="'.$url.'">';

				if ($est_page_archive_activite) {
					echo 'Tous';
					}

				else {
					echo 'Toutes';
					}
					echo '</a></li>';

			foreach ($filtres_taxonomie as $filtre) {
				$classe = '';

				if (isset($_GET[$nom_du_filtre])
					&& $_GET[$nom_du_filtre] === $filtre->slug) {
					$classe = 'filtre_actif';
				}

				echo '<li class="filtre '.$classe.'"><a href="'.

					$url.'?'.$nom_du_filtre .'='.$filtre->slug.'">' . $filtre->name . '</a></li>';
			}
			?>




		</ul>
	<?php endif; ?>

	<div class="content<?php if ( ! is_active_sidebar( 'primary_sidebar' ) ) { echo " no-sidebar"; } ?>">
		<?php if (have_posts()): ?>
			<section id="post-roll" class="post-roll">
				<?php while (have_posts()) : the_post(); ?>
					
					<?php
						$extra_classes = '';
						if ( '' == get_the_post_thumbnail() ) {
							$extra_classes = 'full-width-post';
						}
					?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class( $extra_classes ); ?>>
						
						<?php if ( '' != get_the_post_thumbnail() ) : ?>
							<figure>
								<a href="<?php the_permalink(); ?>" rel="bookmark">
									<?php the_post_thumbnail( 'archive_thumbnail' ); ?>
								</a>
							</figure>
						<?php endif; ?>
						
						<div class="post-content">
							
							<h1 class="heading">
								<a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'Read full article', 'watson' ); ?>" rel="bookmark">
									<?php the_title(); ?>
								</a>
							</h1>
							
							<?php 
							// the_excerpt();
							?>
							
							<nav>
								
								<?php if ( ! watson_option( 'hide_date' ) ) : ?>
									<?php get_template_part( '_date' ); ?>
								<?php endif; ?>
								
								<?php
								// Commentaires désactivés
									/*
								<?php $has_comments = ( get_comments_number() > 0 || comments_open() ); ?>
								<?php if ( $has_comments ) : ?>
									<a class="comment-count" href="<?php comments_link(); ?>" title="<?php esc_attr_e( 'Jump to comments', 'watson' ); ?>"><?php
										comments_number( __( '0 Comments', 'watson' ), __( '1 Comment', 'watson' ), __( '% Comments', 'watson' ) );
									?></a>
								<?php endif; ?> 
									*/
								?>

								<?php
								$type = get_post_type();

								if ($type == 'post') {
									the_terms (get_the_ID(),'activites');
									echo ' | ';
									the_terms (get_the_ID(),'lieux');
								}

								$lire_article_texte = '';

								if ($type == 'post') {
									$lire_article_texte = 'Lire le topo';
								} else if ($type == 'album-voyage' || $type == 'carnet-voyage') {
									$lire_article_texte = 'Découvrir le voyage';
								} else {
									$lire_article_texte = "Lire l'article";
								}
								?>

								<br/>
								<span>
									<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?= $lire_article_texte ?>">
										<?= $lire_article_texte ?>
									</a>
								</span>
							</nav>

						</div>

					</article>

				<?php endwhile; ?>
			</section>
			<nav class="post-footer index-footer">
				<p>
					<?php next_posts_link( 'Plus ancien' ); ?>
					<?php previous_posts_link( 'Plus récent' ); ?>
				</p>
			</nav>
		<?php endif; ?>
	</div>
	<?php 
	// get_sidebar();
	?>
</div>
<?php get_footer(); ?>