<?php
/**
 * @package Watson
 */

add_action( 'save_post', 'watson_save_post_meta' );
add_action( 'ttf_foundry_slider_after_metabox_content', 'watson_post_option_box_html', 10, 2 );

if ( ! function_exists( 'watson_post_option_box_html' ) ) :
	/**
	 * Add a checkbox to include the post in the featured slider
	 */
	function watson_post_option_box_html( $post, $args ) {
		// Add pointer to slider select
	?>
		<a id="featured-slider-info" style="text-decoration:none;" href="#">(?)</a>
	<?php

		// Use nonce for verification
		$watson_nonce = wp_nonce_field( ( 'watson_save_post-' . $post->ID ), 'watson_nonce', true, false );

		// The actual fields for data entry
		echo $watson_nonce;

		$is_thumbnail_checked = get_post_meta( $post->ID, 'watson_featured_thumbnail', true );

		echo '<br />';

		ttf_common_get_meta_checkbox_html(
			'watson_featured_thumbnail',
			$is_thumbnail_checked,
			__( "Show in featured thumbnails", 'watson' ),
			false,
			'featured-thumbnails-info'
		);
	}

endif; // watson_post_option_box_html

if ( ! function_exists( 'watson_save_post_meta' ) ) :
	/**
	 * Check for and save our custom field for adding a post to the featured slider
	 */
	function watson_save_post_meta( $post_id ) {
		// This function is only for posts
		if ( 'post' != get_post_type( $post_id ) )
			return;

		if ( ! watson_has_edit_permissions( $post_id, ( 'watson_save_post-' . $post_id ) ) )
			return;

		// OK, we're authenticated: we need to find and save the data

		// Add or remove the post from featured thumbnails
		$set_featured_thumbnail = isset( $_POST['watson_featured_thumbnail'] ) && (boolean) $_POST['watson_featured_thumbnail'];

		ttf_common_save_meta( $post_id, 'watson_featured_thumbnail', $set_featured_thumbnail );
	}

endif; // watson_save_post_meta


if ( ! function_exists( 'watson_has_edit_permissions' ) ) :
	/**
	 * Checks if the current user has the right permissions and nonce
	 * to edit this post/page
	 */
	function watson_has_edit_permissions( $post_id, $nonce_name ) {
		// Check if this is an auto save routine.
		// If it is the form has not been submitted, so we don't do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		// Check nonce was submitted properly

		if ( ! isset( $_POST['watson_nonce'] ) || ! wp_verify_nonce( $_POST['watson_nonce'], $nonce_name ) ) {
			return false;
		}

		// Check permissions
		$has_edit_permission = current_user_can( 'edit_post', $post_id );

		if ( ! $has_edit_permission ) {
			return false;
		}

		return true;
	}

endif; // watson_has_edit_permissions

if ( ! function_exists( 'watson_get_featured_thumbnails_query' ) ) :
	/**
	 * Returns a query of the posts that are marked as featured thumbnails,
	 * to be shown as a horizontal bar in the post listing.
	 */
	function watson_get_featured_thumbnails_query() {
		return new WP_Query( array(
			'meta_query' => array(
				array(
					'key' => 'watson_featured_thumbnail',
					'value' => true,
					'type' => 'BOOLEAN'
				)
			),
			'posts_per_page' => 4,
			'ignore_sticky_posts' => 1
		) );
	}

endif; // watson_get_featured_thumbnails_query

add_filter( 'ttf_foundry_slider_metabox_title', 'watson_ttf_foundry_slider_metabox_title' );

if ( ! function_exists( 'watson_filter_ttf_foundry_slider_metabox_title' ) ) :

	/**
	 * Alter the title of the Foundry Slider metabox.
	 *
	 * @return string			The modified title.
	 */
	function watson_ttf_foundry_slider_metabox_title() {
		return __( 'Watson Theme Post Options', 'watson' );
	}

endif;
