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
<div id="main" role="main">

	<div class="content">
	<div class="inner-content">
		<div id="featured-slider">
		<?php
			if ( is_home() && ! is_paged() ) {
				get_template_part( '_featured-slider' );
			}
		?>
		</div>
		<section id="post-roll" class="post-roll home-post-roll">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
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
									<?php the_post_thumbnail( 'watson_featured_index' ); ?>
								</a>
							</figure>
						<?php endif; ?>
						<div class="post-content">
							<h1 class="heading">
								<a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'Read full article', 'watson' ); ?>" rel="bookmark">
									<?php the_title(); ?>
								</a>
							</h1>
							<?php the_excerpt(); ?>
							
							<p  
							style="color:#aaa;font-size:0.7rem;font-family: 'Source Sans Pro', Helvetica, Arial, Verdana, Tahoma, sans-serif;">
							<span style="color:#777;">Publié le <?php get_template_part( '_date' ); ?></span><br/>
							dans 
							<?php 
							the_terms (get_the_ID(),'activites'); 
							echo ', '; 
							the_terms (get_the_ID (),'lieux'); 
							?>
							 <b>|</b> <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr_e( 'Read full article', 'watson' ); ?>">
							 	<?php 
							 	_e( 'Lire le topo', 'watson' ); 
							 	?>
							 		
							 	</a></p>
								
							
							<!-- NAV COMMENT ET READ ARTICLE DE WP PAR DEFAUT

							<nav>

								<?php if ( ! watson_option( 'hide_date' ) ) : ?>
									<?php get_template_part( '_date' ); ?>
								<?php endif; ?>


								<?php $has_comments = ( get_comments_number() > 0 || comments_open() ); ?>
								<?php if ( $has_comments ) : ?>
									<a class="comment-count" href="<?php comments_link(); ?>" title="<?php esc_attr_e( 'Jump to comments', 'watson' ); ?>"><?php
										comments_number( __( '0 Comments', 'watson' ), __( '1 Comment', 'watson' ), __( '% Comments', 'watson' ) );
									?></a>
								<?php endif; ?>


								<span>
									<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr_e( 'Read full article', 'watson' ); ?>"><?php _e( 'Read article', 'watson' ); ?></a>
								</span>
							</nav> -->

						</div>
					</article>
					<?php global $wp_query; ?>
					<!-- nombre du if dessous compte le nombre de post +1 avant de placer le featured_thumbnail -->
					<?php if ( 2 == $wp_query->current_post && ! is_paged() ) : ?>
						<?php get_template_part( '_featured-thumbnails' ); ?>
					<?php endif; ?>
				<?php endwhile; ?>
			<?php endif; ?>
		</section>

		<?php
		get_sidebar();
		?>

		<nav id="ancre_moteur_recherche" style="clear:both;" class="post-footer index-footer">
			<p>
				<?php next_posts_link( __( 'Older posts', 'watson' ) ); ?>
				<?php previous_posts_link( __( 'Newer posts', 'watson' ) ); ?>
			</p>
		</nav>

		<div class="search_bloc">
			<?php get_template_part('searchform'); ?>
		</div>

		<!-- <div id="ancre_partage" class="menu_partage">
			<?php echo do_shortcode('[DISPLAY_ULTIMATE_SOCIAL_ICONS]'); ?>
		</div> -->

	</div> <!-- Fin inner-content -->
	</div> <!-- Fin content -->
</div> <!-- Fin Main -->
<?php get_footer(); ?>