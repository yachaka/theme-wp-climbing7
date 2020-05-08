<?php
/**
 * @package Watson
 */
?>
<header class="post-title">
	<h1 class="heading">
		<?php if ( is_singular() ) : ?>
			<?php the_title(); ?>
		<?php else : ?>
			<a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'Read full article', 'watson' ); ?>" rel="bookmark">
				<?php the_title(); ?>
			</a>
		<?php endif; ?>
	</h1>

	<p 
		style="text-align:center;color:#aaa;font-size:0.9rem;font-family: 'Source Sans Pro', Helvetica, Arial, Verdana, Tahoma, sans-serif;font-weight:200;"
	>
		Publié le <?php get_template_part( '_date' ); ?>
		dans <?php the_terms (get_the_ID (), 'activites'); echo ', '; the_terms (get_the_ID(), 'lieux'); ?> |

		<?php
		$has_comments = ( get_comments_number() > 0 || comments_open() );

		if ( $has_comments ) : ?>
			<a
				class="comment-count"
				href="<?php comments_link(); ?>"
				title="<?php esc_attr_e( 'Jump to comments', 'watson' ); ?>"
			>
				<?php
					comments_number( __( '0 Comments', 'watson' ), __( '1 Comment', 'watson' ), __( '% Comments', 'watson' ) );
				?>	
			</a>
		<?php endif; ?>
	</p>

	<!-- <?php if ( ! is_page() ) : ?>
		<p class="post-byline">
			<?php if ( ! watson_option( 'hide_author' ) ) : ?>
				<?php _e( 'By ', 'watson' ); ?>
				<span class="post-author"><?php the_author_posts_link(); ?></span><?php echo ( ! watson_option( 'hide_date' ) ) ? ', ' : ''; ?>
			<?php endif; ?>
			<?php if ( ! watson_option( 'hide_date' ) ) : ?>
				<?php if ( is_singular() ) : ?>
					<?php get_template_part( '_date' ); ?>
				<?php else : ?>
					<a href="<?php the_permalink(); ?>" rel="bookmark">
						<?php get_template_part( '_date' ); ?>
					</a>
				<?php endif; ?>
			<?php endif; ?>
		</p>
	<?php endif; ?> -->
</header>