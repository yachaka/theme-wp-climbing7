<?php
/**
 * @package Watson
 */

add_action( 'init', 'watson_tinymce_plugin' );
add_action( 'admin_enqueue_scripts', 'watson_enqueue_tinymce_styles' );

if ( ! function_exists( 'watson_tinymce_plugin' ) ) :
	/**
	 * Hooks up our editor plugin, which adds some custom buttons to the editor
	 */
	function watson_tinymce_plugin() {
		// Don't bother loading buttons if the current user lacks permissions
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) )
			return;

		// Add only in Rich Editor mode
		if ( get_user_option( 'rich_editing' ) == 'true' ) {
			add_filter( 'mce_external_plugins', 'watson_add_tinymce_plugin' );
			add_filter( 'mce_buttons_3', 'watson_register_tinymce_buttons' );
		}
	}

endif; // watson_tinymce_plugin

if ( ! function_exists( 'watson_register_tinymce_buttons' ) ) :

	function watson_register_tinymce_buttons( $buttons ) {
		array_push(
			$buttons,
			"hr", "alert", "error", "success", "note",
			"footnote", "sidenote", "run_in", "excerpt", "end"
		);
		return $buttons;
	}

endif; // watson_register_tinymce_buttons

if ( ! function_exists( 'watson_add_tinymce_plugin' ) ) :

	function watson_add_tinymce_plugin( $plugin_array ) {
		$plugin_array['watson'] = get_template_directory_uri() . '/includes/tinymce/plugin/editor_plugin.js?v=' . WATSON_VERSION;
		return $plugin_array;
	}

endif; // watson_add_tinymce_plugin

if ( ! function_exists( 'watson_enqueue_tinymce_styles' ) ) :
	/**
	 * Enqueues the stylesheets for the custom TinyMCE plugin
	 */
	function watson_enqueue_tinymce_styles() {
		wp_enqueue_style(
		'watson_tinymce_plugin_css',
			get_template_directory_uri() . '/includes/tinymce/plugin/style.css',
			array(),
			null
		);
	}

endif; // watson_enqueue_tinymce_styles