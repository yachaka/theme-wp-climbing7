<?php
/**
 * @package Watson
 */
?>
<?php get_header(); ?>
<div role="main">
	<?php if ( have_posts() ) : the_post(); ?>
		<p style="font-size:0.7rem;font-weight:100;width:75px;border-radius:15px;text-align:center;margin:auto;background-color: #DE3163;color:#fff;letter-spacing: 0.1rem;margin-top:20px;margin-bottom:10px;padding:2px 0 1px 0;">TOPOS</p>
		<h3 style="font-size:40px;text-align:center;" class="subheading"><?php ttf_common_archives_title(); ?></h3>
			
		
		<?php if (is_tag ()) { ?>
			<p style="color:#aaa;font-weight: 100;text-align:center;">Filtrer par activités</p>
			<ul class="liste_pays">
				<?php 
				$activites = get_categories ();
				foreach ($activites as $activite) {	
					echo '<li>' . $activite->name . '</li>';
				}
				?>
			</ul>
		<?php
		}
		else if (is_category()) { ?>
			<p style="color:#aaa;font-weight: 100;text-align:center;">Filtrer par pays</p>
			<ul class="liste_pays">
				<?php 

				$payss = get_tags ();
				foreach ($payss as $pays) {	
					echo '<li>' . $pays->name . '</li>';
				}
				?>
		</ul>
		<?php
		}
		?>
		

	<?php rewind_posts(); ?>
	<?php echo wp_kses_post( category_description() ); ?>
	<?php endif; ?>
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
								
								<!-- <?php $has_comments = ( get_comments_number() > 0 || comments_open() ); ?>
								<?php if ( $has_comments ) : ?>
									<a class="comment-count" href="<?php comments_link(); ?>" title="<?php esc_attr_e( 'Jump to comments', 'watson' ); ?>"><?php
										comments_number( __( '0 Comments', 'watson' ), __( '1 Comment', 'watson' ), __( '% Comments', 'watson' ) );
									?></a>
								<?php endif; ?> -->

								<?php the_tags ('') ?>

								<br/>
								<span>
									<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr_e( 'Read full article', 'watson' ); ?>"><?php _e( 'Read article', 'watson' ); ?></a>
								</span>
							</nav>

						</div>

					</article>

				<?php endwhile; ?>
			</section>
			<nav class="post-footer index-footer">
				<p>
					<?php next_posts_link( __( 'Older posts', 'watson' ) ); ?>
					<?php previous_posts_link( __( 'Newer posts', 'watson' ) ); ?>
				</p>
			</nav>
		<?php endif; ?>
	</div>
	<?php 
	// get_sidebar();
	?>
</div>
<?php get_footer(); ?>