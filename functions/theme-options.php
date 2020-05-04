<?php
/**
 * @package Watson
 */

if ( ! function_exists( 'watson_options_init' ) ) :
	/**
	 * Create and initialize the theme options.
	 * Uses the Struts options framework.
	 * https://github.com/thethemefoundry/struts/
	 */
	function watson_options_init() {
		locate_template( array( 'includes/struts/classes/struts.php' ), true );

		Struts::load_config( array(
			'struts_root_uri' => get_template_directory_uri() . '/includes/struts', // required, set this to the URI of the root Struts directory
			'preview_javascript' => get_template_directory_uri() . '/includes/javascripts/previewer.js',
			'use_struts_skin' => true, // optional, overrides the Settings API html output
		) );

		global $watson_options;

		$watson_options = new Struts_Options( 'watson', 'theme_watson_options' );

		// Setup the option sections
		$watson_options->add_section( 'logo_featured_slider_section', __( 'Logo &amp; featured slider', 'watson' ), 200 );
		$watson_options->add_section( 'display_options_section', __( 'Display options', 'watson' ), 201 );
		$watson_options->add_section( 'subscribe_copyright_section', __( 'Subscribe links &amp; copyright', 'watson' ), 204 );

		/* Logo and featured slider section
		 * ------------------------------------------------------------------ */

		$watson_options->add_option( 'logo_url', 'image', 'logo_featured_slider_section' )
			->default_value( '' )
			->label( __( 'Logo image:', 'watson' ) )
			->description( __( 'Upload an image (making sure to click <strong>"Insert into post"</strong>) or enter an <abbr title="Universal resource locator">URL</abbr> for your image. If you choose not to upload a logo, your site\'s title will be used for the header.', 'watson' ) );

		$watson_options->add_option( 'hi_res_logo_image', 'image', 'logo_featured_slider_section' )
			->default_value( '' )
			->label( __( 'High resolution logo', 'watson' ) )
			->description( __( 'Upload a high resolution logo image for Retina (and similar hi-res) devices. This logo will be scaled down 50% and served only to high resolution devices.', 'watson' ) );

		$watson_options->add_option( 'autostart_slider', 'checkbox', 'logo_featured_slider_section' )
			->default_value( false )
			->label( __( 'Autostart slider', 'watson' ) )
			->description( __( 'Check to automatically start slideshow animation for the featured slider.', 'watson' ) );

		/* Display section
		 * ------------------------------------------------------------------ */

		$watson_options->add_option( 'hide_tags', 'checkbox', 'display_options_section' )
			->default_value( false )
			->label( __( 'Hide tags', 'watson' ) )
			->description( __( 'Check to hide tags in posts.', 'watson' ) );

		$watson_options->add_option( 'hide_categories', 'checkbox', 'display_options_section' )
			->default_value( false )
			->label( __( 'Hide categories', 'watson' ) )
			->description( __( 'Check to hide categories in posts.', 'watson' ) );

		$watson_options->add_option( 'hide_author', 'checkbox', 'display_options_section' )
			->default_value( false )
			->label( __( 'Hide author', 'watson' ) )
			->description( __( 'Check to hide the author in posts.', 'watson' ) );

		$watson_options->add_option( 'hide_date', 'checkbox', 'display_options_section' )
			->default_value( false )
			->label( __( 'Hide date', 'watson' ) )
			->description( __( 'Check to hide dates throughout your site.', 'watson' ) );

		$watson_options->add_option( 'hide_post_featured', 'checkbox', 'display_options_section' )
			->default_value( false )
			->label( __( 'Hide post featured image', 'watson' ) )
			->description( __( 'Check to hide featured images in the single blog post view.', 'watson' ) );

		$watson_options->add_option( 'hide_post_nav', 'checkbox', 'display_options_section' )
			->default_value( false )
			->label( __( 'Hide post navigation', 'watson' ) )
			->description( __( 'Check to hide post navigation in the single blog post view.', 'watson' ) );

		$watson_options->add_option( 'disable_web_font', 'checkbox', 'display_options_section' )
			->default_value( false )
			->label( __( 'Disable web fonts', 'watson' ) )
			->description( __( 'Check to turn off web fonts.', 'watson' ) );

		$watson_options->add_option( 'primary_color', 'color', 'display_options_section' )
			->default_value( '#d00f1a' )
			->label( __( 'Primary color', 'watson' ) )
			->description( __( 'Choose the primary color for your theme. The primary color is used for link text and other accents.', 'watson' ) )
			->preview_function( 'watsonThemePreviewPrimaryColor' );

		/* Footer & Subscribe links section
		 * ------------------------------------------------------------------ */

		$watson_options->add_option( 'twitter_url', 'text', 'subscribe_copyright_section' )
			->label( __( 'Twitter <abbr title="Universal resource locator">URL</abbr>:', 'watson' ) )
			->description( __( 'Enter your Twitter link.', 'watson' ) )
			->is_url( true );

		$watson_options->add_option( 'facebook_url', 'text', 'subscribe_copyright_section' )
			->label( __( 'Facebook <abbr title="Universal resource locator">URL</abbr>:', 'watson' ) )
			->description( __( 'Enter your Facebook link.', 'watson' ) )
			->is_url( true );

		$watson_options->add_option( 'google_url', 'text', 'subscribe_copyright_section' )
			->label( __( 'Google+ <abbr title="Universal resource locator">URL</abbr>:', 'watson' ) )
			->description( __( 'Enter your Google+ link.', 'watson' ) )
			->is_url( true );

		$watson_options->add_option( 'flickr_url', 'text', 'subscribe_copyright_section' )
			->label( __( 'Flickr <abbr title="Universal resource locator">URL</abbr>:', 'watson' ) )
			->description( __( 'Enter your Flickr link.', 'watson' ) )
			->is_url( true );

		$watson_options->add_option( 'hide_icons', 'checkbox', 'subscribe_copyright_section' )
			->default_value( false )
			->label( __( 'Disable all icons', 'watson' ) )
			->description( __( 'Check to hide all subscribe icons (including <abbr title="Really Simple Syndication">RSS</abbr>). This option overrides all other icon settings.', 'watson' ) );

		$watson_options->add_option( 'copyright_text' , 'text', 'subscribe_copyright_section' )
			->label( __( 'Credit line text:', 'watson' ) )
			->description( __( "Enter your credit text to be displayed in the theme footer. Basic HTML is allowed.", 'watson' ) );
	}

endif; // watson_options_init

if ( ! function_exists( 'watson_option' ) ) :

	// Gets the value for a requested option.

	function watson_option( $option_name ) {
		global $watson_options;

		return $watson_options->get_value( $option_name );
	}

endif; // watson_option
