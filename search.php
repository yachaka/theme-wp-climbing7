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
?>
<?php get_header(); ?>

<div role="main">

	<div
		id="search_bloc"
		class="search_bloc"
	>
		<?php get_template_part('searchform'); ?>
	</div>

	<script type="text/javascript">
    	var elementBascule = document.getElementById('search_bloc');
    	var elementDeclencheur = elementBascule.querySelector('h3');

    	elementDeclencheur.addEventListener('click', function () {
    		basculementClasse(elementBascule);
    	});

	 	function basculementClasse(element) {
		    if (element.classList.contains('ouvert')) {
		      element.classList.remove('ouvert');
		    } else {
		      element.classList.add('ouvert');
		    }
		}
	</script>

	<p id="pastille">
		TOPOS
	</p>

	<h3 id="titre" class="subheading">
		<?php 
		if (get_search_query()) {
			?>
			Résultats de recherche pour "<?= get_search_query(); ?>"
			<?php
			}
		else {
			echo 'Résultats de la recherche';
			}
		?>
	</h3>
	

	<div class="content<?php if ( ! is_active_sidebar( 'primary_sidebar' ) ) { echo " no-sidebar"; } ?>">
		<?php if ( have_posts() ) : ?>

			<section id="post-roll" class="post-roll">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						$extra_classes = '';
						if ( '' == get_the_post_thumbnail() ) {
							$extra_classes = 'full-width-post';
						}
					?>
					
					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						
						<div class="post-content">

							<?php if ( '' != get_the_post_thumbnail() ) : ?>
								<figure>
									<a href="<?php the_permalink(); ?>" rel="bookmark">
										<?php the_post_thumbnail( 'archive_thumbnail' ); ?>
									</a>
								</figure>
							<?php endif; ?>
							
							<h1 class="heading">
								<a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'Read full article', 'watson' ); ?>" rel="bookmark">
									<?php the_title(); ?>
								</a>
							</h1>

							<?php 
							// get_template_part( '_post-header' ); 
							?>
							
							<nav>
								<?php if ( ! watson_option( 'hide_date' ) ) : ?>
									<?php get_template_part( '_date' ); ?>
								<?php endif; ?>
								
								<?php 
									the_terms (get_the_ID(),'activites');
									echo ' | ';
									the_terms (get_the_ID(),'lieux'); 
									?>

								<?php 
								// the_excerpt();
								?>
								
								<br/>
								<span>
									<span>
									<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr_e( 'Read full article', 'watson' ); ?>"><?php _e( 'Read article', 'watson' ); ?></a>
								</span>
								</span>
							</nav>
						</div>

					</div>

				<?php endwhile; ?>
				
				<nav class="post-footer index-footer">
					<p>
						<?php next_posts_link( __( 'Older posts', 'watson' ) ); ?>
						<?php previous_posts_link( __( 'Newer posts', 'watson' ) ); ?>
					</p>
				</nav>
			</section> 

				<?php else : ?>
			
				
				<div>
					<p style="text-align:center;"><?php printf( __( 'Désolé, la recherche %s ne donne aucun résultat. Essaye de nouveau en modifiant les critères de recherche ?', 'watson' ), get_search_query());?></p>
					<?php
					// get_search_form();
					?>
				</div>

			<?php endif; ?>

	</div>
	

	<?php 
	// get_sidebar(); 
	?>
</div>
<?php get_footer(); ?>