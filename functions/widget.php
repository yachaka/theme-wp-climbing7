<?php
/**
 * @package Watson
 */

add_action( 'widgets_init', 'watson_register_widgets' );

class WatsonThemeRecentlyDiscussedWidget extends WP_Widget {

	function WatsonThemeRecentlyDiscussedWidget() {
		// Instantiate the parent object
		parent::__construct(
			false,
			__( 'Watson Theme Recently Discussed', 'watson' ),
			array( 'description' => __( 'Posts with recent comments. Shows post featured image, title and time of last comment.', 'watson' ) ) );
	}

	function widget( $args, $instance ) {
		// Only look for recently discussed if the transient doesn't exist
		if ( false === ( $recently_discussed_posts = get_transient( 'watson_widget_recently_discussed_posts' ) ) ) {
			$recently_discussed_posts = array();
			$number_to_fetch = 1;
			$offset = 0;

			do {
				$recent_comments = get_comments( array (
					'number' => $number_to_fetch,
					'status' => 'approve',
					'offset' => $offset
				) );

				// Extract post parents from recent comments
				foreach ( $recent_comments as $recent_comment ) {
					$post_id = $recent_comment->comment_post_ID;

					// Since the comments are ordered by date, if we already have an entry for the post,
					// we don't want to overwrite it with an older comment
					if ( ! isset( $recently_discussed_posts[$post_id] ) ) {
						$recently_discussed_posts[$post_id] = array(
							'id' => $post_id,
							'permalink' => get_permalink( $post_id ),
							'thumbnail' => get_the_post_thumbnail( $post_id, 'watson_recent_posts_widget' ),
							'title' => get_the_title( $post_id ),
							'comment_time' => mysql2date( get_option( 'date_format' ), $recent_comment->comment_date )
						);
					}

					// Limit recently discussed posts to 3
					if ( 3 === count( $recently_discussed_posts ) ) {
						break;
					}
				}

				// Keep moving through the list of comments
				$offset += $number_to_fetch;
			} while( count( $recently_discussed_posts ) < 3 && count( $recent_comments ) >= $number_to_fetch );
			// We want to keep looking for recent comments until we have 3 posts, or we've searched all the comments

			set_transient( 'watson_widget_recently_discussed_posts', $recently_discussed_posts, 60*15 );
		}

		?>
		<?php if ( ! empty( $recently_discussed_posts ) ) : ?>
			<?php echo $args['before_widget']; ?>
			<div class="custom-recently-discussed">
				<h3 class="widgettitle"><?php _e( 'Recently discussed', 'watson' ); ?></h3>
				<?php foreach ( $recently_discussed_posts as $post_info ) : ?>
					<article class="recent-post">
						<a href="<?php echo esc_attr( $post_info['permalink'] ); ?>" rel="bookmark">
							<?php echo $post_info['thumbnail']; ?>
						</a>
						<section>
							<a href="<?php echo esc_attr( $post_info['permalink'] ); ?>" rel="bookmark">
								<h4><?php echo $post_info['title']; ?></h4>
							</a>
							<a href="<?php echo esc_attr( $post_info['permalink'] ); ?>" rel="bookmark">
								<time><?php echo $post_info['comment_time']; ?></time>
							</a>
						</section>
					</article>
				<?php endforeach; ?>
			</div>
			<?php echo $args['after_widget']; ?>
		<?php endif; ?>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
	}

	function form( $instance ) {
		// Output admin widget options form
	}
}

if ( ! function_exists( 'watson_register_widgets' ) ) :

	function watson_register_widgets() {
		register_widget( 'watsonThemeRecentlyDiscussedWidget' );
	}

endif; // watson_register_widgets