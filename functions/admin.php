<?php
/**
 * @package Watson
 */

add_action( 'admin_enqueue_scripts', 'watson_enqueue_admin_scripts' );

if ( ! function_exists( 'watson_enqueue_admin_scripts' ) ) :
	/**
	 * Enqueue any admin scripts to be served on the backend
	 */
	function watson_enqueue_admin_scripts() {
		wp_enqueue_style(
			'watson_tinymce_plugin_css',
			get_template_directory_uri() . '/includes/tinymce/plugin/style.css',
			array(),
			null
		);

		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
	}

endif; // watson_enqueue_admin_scripts
if ( ! function_exists( 'watson_setup_admin_pointers' ) ) :
	/**
	 * Setups up the $ttf_admin_pointer global with the pointers this theme needs
	 */
	function watson_setup_admin_pointers() {
		$featured_slider_content = '<h3>' . __( 'Featured slider', 'watson' ) . '</h3>';
		$featured_slider_content .= '<p>' . __( 'Posts in the <strong>Featured slider</strong> rotate on the home page, above your regular post list. Each post in the slider will display a large featured image, the post title, and a small excerpt.', 'watson' ) . '</p>';

		ttf_common_add_admin_pointer(
			'a#featured-slider-info',
			array( 'post-new.php', 'post.php' ),
			'post',
			$featured_slider_content,
			array(
				'offset' => '-25 0',
				'align' => 'left',
				'edge' => 'right'
			)
		);

		$featured_thumbnail_content = '<h3>' . __( 'Featured thumbnails', 'watson' ) . '</h3>';
		$featured_thumbnail_content .= '<p>' . __( 'The <strong>Featured thumbnails</strong> section is perfect for including teasers of your favourite posts on your site&#8217s home page. This section displays up to four posts of your choice. Each featured thumbnail post displays a small thumbnail image and post title.', 'watson' ) . '</p>';

		ttf_common_add_admin_pointer(
			'a#featured-thumbnails-info',
			array( 'post-new.php', 'post.php' ),
			'post',
			$featured_thumbnail_content,
			array(
				'offset' => '-25 0',
				'align' => 'left',
				'edge' => 'right'
			)
		);

		$editor_buttons_content = '<h3>' . __( 'Custom editor buttons', 'watson' ) . '</h3>';
		$editor_buttons_content .= '<p>' . __( 'Watson provides custom formatting buttons for styling your content. Click the "show kitchen sink" button to view Watson&#8217s formatting buttons.', 'watson' ) . '</p>';

		ttf_common_add_admin_pointer(
			'a#content_wp_adv',
			array( 'post-new.php', 'post.php' ),
			'post',
			$editor_buttons_content,
			array(
				'offset' => '0 0',
				'align' => 'right',
				'edge' => 'left'
			),
			true,
			'watson_theme_kitchen_sink_pointer'
		);

		$page_template_content = '<h3>' . __( 'Page templates', 'watson' ) . '</h3>';
		$page_template_content .= '<p>' . __( 'Watson provides 4 page templates, each with slightly different layouts and functionality. The default page template is center-aligned (not full-width) without a sidebar.', 'watson' ) . '</p>';

		ttf_common_add_admin_pointer(
			'select#page_template',
			array( 'post-new.php', 'post.php' ),
			'page',
			$page_template_content,
			array(
				'offset' => '-20 0',
				'align' => 'left',
				'edge' => 'right'
			),
			true,
			'watson_theme_page_template_pointer'
		);
	}

endif; // watson_setup_admin_pointers
