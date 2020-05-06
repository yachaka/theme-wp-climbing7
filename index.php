<?php
/**
 * @package Watson
 */
?>
<?php get_header(); ?>
<div id="main" role="main">
	<div class="content">
	<div class="inner-content">
		<div id="featured-slider" style="margin-bottom:30px;border-bottom: 1px solid #e3e3e3;">
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
							Publié le <?php get_template_part( '_date' ); ?> dans 
							<?php 
							the_terms (get_the_ID(),'activity'); 
							echo ', '; 
							the_terms (get_the_ID (),'region'); 
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
		<nav style="clear:both;" class="post-footer index-footer">
			<p>
				<?php next_posts_link( __( 'Older posts', 'watson' ) ); ?>
				<?php previous_posts_link( __( 'Newer posts', 'watson' ) ); ?>
			</p>
		</nav>
		
		<div id="search_bloc" style="padding:20px 20px 30px 20px;margin-top:40px;background-color: #efefef;">
			<h3 style="text-align:center;">Moteur de recherche</h3>
			<div style="text-align: center;" >
				<form>
					<label style="font-family: 'Helvetica'; font-size:0.8rem;">Pour un lieu, un topo</label><br/>
  					<input style="width:400px;border-radius:9px;" type="text" id="fname" name="fname"><br>
  					<input type="submit" value="Valider">
  				</form> 

  				<form>
					<label style="font-family: 'Helvetica'; font-size:0.8rem;">En filtrant par activité et par pays</label><br/>
  					<input style="width:150px;border-radius:9px;" type="text" id="fname" name="fname" value="activité"><input style="width:150px;border-radius:9px;" type="text" id="fname" name="fname" value="pays"><br>
  					<input type="submit" value="Valider">
  				</form> 
  			</div>
		</div>




	</div>
	</div>
</div>
<?php get_footer(); ?>