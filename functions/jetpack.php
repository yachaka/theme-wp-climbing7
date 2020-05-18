<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Watson
 */

/**
 * Retrieve Watson options
 */
$watson_options = get_option( 'theme_watson_options' );
global $watson_hide_date;
$watson_hide_date = (boolean) isset($watson_options[ 'hide_date' ]) ? $watson_options[ 'hide_date' ] : false;

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function watson_infinite_scroll_init() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'post-roll',
		'footer_widgets' => ( ( ( class_exists( 'Jetpack_User_Agent_Info' ) && method_exists( 'Jetpack_User_Agent_Info', 'is_ipad' ) && Jetpack_User_Agent_Info::is_ipad() ) || ( function_exists( 'jetpack_is_mobile' ) && jetpack_is_mobile() ) ) || is_active_sidebar( 'footer_1' ) || is_active_sidebar( 'footer_2' ) ),
		'footer' => 'main',
	) );
}
add_action( 'after_setup_theme', 'watson_infinite_scroll_init' );

/**
 * Set the code to be rendered on for calling posts, hooked to template parts when possible.
 *
 * Note: must define a loop.
 */
function watson_infinite_scroll_render() {
	global $watson_hide_date;

	while ( have_posts() ) : the_post();
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
				<nav>
					<?php if ( isset( $watson_hide_date ) && false === $watson_hide_date ) : ?>
						<date><?php the_time( get_option( 'date_format' ) ); ?></date>
					<?php endif; ?>
					<a class="comment-count" href="<?php comments_link(); ?>" title="<?php esc_attr_e( 'Jump to comments', 'watson' ); ?>"><?php
						comments_number( __( '0 Comments', 'watson' ), __( '1 Comment', 'watson' ), __( '% Comments', 'watson' ) );
						?></a>
					<span>
						<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr_e( 'Read full article', 'watson' ); ?>"><?php _e( 'Read article', 'watson' ); ?></a>
					</span>
				</nav>
			</div>
		</article>
	<?php endwhile;
}
add_action( 'infinite_scroll_render', 'watson_infinite_scroll_render' );
