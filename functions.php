<?php
/**
 * @package Watson
 */

/**
 * Theme version
 */
define( 'WATSON_VERSION', '1.1.9' );

// Require the other functions files
locate_template( array( 'functions/admin.php' ), true );
locate_template( array( 'functions/post-options.php' ), true );
locate_template( array( 'functions/theme-options.php' ), true );
locate_template( array( 'functions/tinymce-plugin.php' ), true );
locate_template( array( 'functions/ttf-common/ttf-common.php' ), true );
locate_template( array( 'functions/foundry-slider/foundry-slider.php' ), true );
locate_template( array( 'functions/widget.php' ), true );
locate_template( array( 'functions/jetpack.php' ), true );

// Setup content width
if ( ! isset( $content_width ) ) {
	$content_width = 492;
}

function watson_set_content_width() {
	global $content_width;

	if ( is_page_template( 'full-width.php' ) ) {
		$content_width = 900;
	} elseif ( is_page_template( 'with-sidebar.php' ) || is_page_template( 'sitemap.php' ) ) {
		$content_width = 550;
	}

}
add_action( 'template_redirect', 'watson_set_content_width' );

add_action( 'widgets_init', 'my_register_sidebars' );
function my_register_sidebars() {
		/* Register the 'primary' sidebar. */
		register_sidebar(
				array(
						'id'            => 'post_sidebar',
						'name'          => __( 'Sidebar Post' ),
						'description'   => __( 'Je suis la description de la sidebar du post' ),
						'before_widget' => '<div id="%1$s" class="widget %2$s">',
						'after_widget'  => '</div>',
						'before_title'  => '<h3 class="widget-title">',
						'after_title'   => '</h3>',
				)
		);
		/* Repeat register_sidebar() code for additional sidebars. */
}

// We have 2 choices for slider. Use ResponsiveSlides for this theme.
define( 'TTF_USE_RESPONSIVESLIDES', true );

add_action( 'after_setup_theme', 'watson_setup' );

if ( ! function_exists( 'watson_setup' ) ) :

	function watson_setup() {
		// Attempt to load from child theme first, then parent
		if ( ! load_theme_textdomain( 'watson', get_stylesheet_directory() . '/languages' ) ) {
			load_theme_textdomain( 'watson', get_template_directory() . '/languages' );
		}

		watson_setup_color_selectors();
		watson_setup_admin_pointers();
		watson_options_init();


		add_theme_support( 'post-thumbnails' );

		// Editor styles
		$editor_styles = array();
		if ( ! watson_option( 'disable_web_font' ) ) {
			$editor_styles[] = '//fonts.googleapis.com/css?family=PT+Serif:400';
			$editor_styles[] = '//fonts.googleapis.com/css?family=PT+Serif:700';
			$editor_styles[] = '//fonts.googleapis.com/css?family=PT+Serif:400italic';
			$editor_styles[] = '//fonts.googleapis.com/css?family=PT+Serif:700italic';
		}
		$editor_styles[] = 'includes/stylesheets/editor-style.css';
		add_editor_style( $editor_styles );

		// Version 3.4 introduced a new way to register support for custom backgrounds
		if ( function_exists( 'wp_get_theme' ) ) {
			add_theme_support( 'custom-background' );
		} else {
			add_custom_background();
		}

		add_filter( 'wp_title', 'ttf_common_page_title' );
		add_filter( 'next_posts_link_attributes', 'ttf_common_link_rel_next' );
		add_filter( 'previous_posts_link_attributes', 'ttf_common_link_rel_prev' );
		add_filter( 'body_class', 'ttf_common_body_class' );
		add_filter( 'posts_where', 'ttf_common_remove_password_posts' );
		add_filter( 'post_gallery', 'ttf_common_gallery_display', 10, 2 );

		add_action( 'admin_print_footer_scripts', 'ttf_common_admin_print_footer_pointer_scripts' );

		register_nav_menu( 'primary', __( 'Primary Menu', 'watson' ) );

		add_image_size( 'watson_featured', 900, 600, true ); // avant 608 400
		add_image_size( 'watson_featured_index', 205, 145, true );
		add_image_size( 'watson_recent_posts_widget', 88, 64, true );
		add_image_size( 'watson_featured_thumbnail', 115, 75, true );
		add_image_size( 'archive_thumbnail', 300, 200, true );

	}

endif; // watson_setup

if ( ! function_exists( 'watson_setup_color_selectors' ) ) :
	/**
	 * Sets up a global to hold some CSS selector values that need to be used in multiple places
	 */
	function watson_setup_color_selectors() {
		global $watson_color_selectors;

		$watson_color_selectors = array(
			'font' =>
				'a, h5, .run-in, .end:after, a.custom-more-link, .post-roll article.post .post-content nav span a,
				footer.post-footer a[rel="prev"]:before, footer.post-footer a[rel="next"]:after, .widget_watsonthemerecentlydiscussedwidget .recent-post:hover h4:after,
				span.required, .responsive-slides a.prev:before, .responsive-slides a.next:after, .featured-article p span'
		);
	}

endif; // watson_setup_color_selectors

if ( ! function_exists( 'watson_get_color_styles' ) ) :
	/**
	 * Lots of HTML printing to allow customization of colors.
	 */
	function watson_get_color_styles() {
		// Grab the primary color selected on the theme options page
		$primary_color = esc_html( watson_option( 'primary_color' ) );

		if ( '#d00f1a' === $primary_color )
			return '';

		global $watson_color_selectors;

		// All selectors to use CSS 'color' attribute
		$primary_font_color_selectors = $watson_color_selectors['font'];

		// Apply the primary color to the primary selectors
		$styles = "<style type=\"text/css\" id=\"watson-color-styles\">\n
			$primary_font_color_selectors { color: $primary_color; }\n
		</style>";

		return $styles;
	}

endif; // watson_get_color_styles
add_action( 'wp_head', 'watson_print_header_items' );

if ( ! function_exists( 'watson_print_header_items' ) ) :
	/**
	 * Prints header styles and JavaScript for the theme
	 */
	function watson_print_header_items() {
		?>
		<script type="text/javascript">
			var watsonThemeMenuText = '<?php echo esc_js( __( 'Go to&hellip;', 'watson' ) ); ?>';
			<?php watson_slider_javascript(); ?>
		</script>
			<?php echo watson_get_color_styles(); ?>
		<?php
	}

endif; // watson_print_header_items

if ( ! function_exists( 'watson_slider_javascript' ) ) :
	/**
	 * JavaScript for powering all sliders
	 */
	function watson_slider_javascript() {
		?>
		(function($){
			$(document).ready(function(){
				$('.responsive-slides .slides').each(function() {
					var dataAutostart = $(this).parent().attr('data-autostart');
					var autostart = ! (typeof dataAutostart === 'undefined');

					$(this).responsiveSlides({
						nav: true,
						auto: autostart,
						prevText: '<?php echo esc_js( __( 'Previous', 'watson' ) ); ?>',
						nextText: '<?php echo esc_js( __( 'Next', 'watson' ) ); ?>',
						controls: 'ul.rslides-direction-nav',
						timeout: 7000
					});
				});
			});
		})(jQuery);
		<?php
	}

endif; // watson_slider_javascript

add_action( 'wp_head', 'watson_possibly_disable_web_font', 99 );

if ( ! function_exists( 'watson_possibly_disable_web_font' ) ) :
	/**
	 * Remove web fonts if option is selected.
	 *
	 * If the user has elected to disable web fonts, rewrite the font stack wherever it is applied
	 *
	 * @return void
	 */
	function watson_possibly_disable_web_font() {
		$disable_web_font = watson_option( 'disable_web_font' );

		if ( empty( $disable_web_font ) )
			return;
		?>

		<style type="text/css" id="watson-color-styles">
			body, body.blog .responsive-slides p {
				font-family: Georgia, Cambria, 'Times New Roman', Times, serif;
			}
			textarea,
			select,
			input[type="date"],
			input[type="datetime"],
			input[type="datetime-local"],
			input[type="email"],
			input[type="month"],
			input[type="number"],
			input[type="password"],
			input[type="search"],
			input[type="tel"],
			input[type="text"],
			input[type="time"],
			input[type="url"],
			input[type="week"],
			h1,
			table,
			h3.widgettitle,
			body.blog .responsive-slides .featured-article p span,
			.sd-like-count,
			/* From the mixin */
			#comments,
			.comments-rss,
			.dk_container,
			button,
			input[type="reset"],
			input[type="submit"],
			input[type="button"],
			.widget_tag_cloud a,
			.wp_widget_tag_cloud a,
			.post-edit-link,
			a#cancel-comment-reply-link,
			.comment-edit-link,
			.index-footer p a,
			.page-links,
			.tmp-attachment nav,
			.tmp-attachment figure a,
			a.custom-more-link,
			.wp-caption-text,
			.feature figcaption,
			dd.wp-caption-dd,
			header.post-title .post-byline,
			.post-roll article.post .post-content nav,
			footer.post-footer,
			.featured-thumbnails-container .featured-thumbnail,
			blockquote cite,
			details,
			.footnote,
			.alert,
			nav[role="navigation"],
			footer[role="contentinfo"],
			aside[role="complementary"],
			.responsive-slides {
				font-family: Helvetica, Arial, Verdana, Tahoma, sans-serif;
			}
			#respond {
				font-family: Helvetica, Arial, Verdana, Tahoma, sans-serif !important;
			}
		</style>

		<?php
	}

endif;

add_action( 'widgets_init', 'watson_register_sidebar' );

if ( ! function_exists( 'watson_register_sidebar' ) ) :
	/**
	 * Register the sidebars
	 */
	function watson_register_sidebar() {
		register_sidebar( array(
			'name'=> __( 'Sidebar', 'watson' ),
			'id' => 'primary_sidebar',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3>'
		) );

		register_sidebar( array(
			'name'=> __( 'Footer Left', 'watson' ),
			'id' => 'footer_1',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3>'
		) );

		register_sidebar( array(
			'name'=> __( 'Footer Right', 'watson' ),
			'id' => 'footer_2',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3>'
		) );
	}

endif; // watson_register_sidebar

add_action( 'admin_init', 'watson_foundry_slider_upgrade' );

if ( ! function_exists( 'watson_foundry_slider_upgrade' ) ) :
	/**
	 * Upgrades the old meta key to the new meta key.
	 *
	 * This function will run on init. It is light enough that it should be ok. Since there is no guarantee that an
	 * will visit the admin after updating, this must run on init.
	 *
	 * @return void
	 */
	function watson_foundry_slider_upgrade() {
		// Don't run upgrade when customizer runs
		global $wp_customize;
		if ( is_a( $wp_customize, 'WP_Customize_Manager' ) && $wp_customize->is_preview() )
			return;

		$option_key       = 'ttf_foundry_slider_watson_version';
		$previous_version = (int) get_option( $option_key, 0 );
		$current_version  = 1;

		// Don't run the upgrader if current is not greater then previous
		if ( $current_version <= $previous_version )
			return;

		// Only need to update if previous version is < 1; future upgrades can be added as elseif clauses
		if ( $previous_version < 1 ) {
			// Get all posts with the previous post meta
			$slider_post_ids = new WP_Query( array(
				'post_status'    => 'publish',
				'fields'         => 'ids',
				'meta_query'     => array(
					array(
						'key'   => 'watson_featured_slider',
						'value' => '1',
					),
				),
				'no_found_rows'  => true,
				'posts_per_page' => 999,
			) );

			// Foreach post with "watson_featured_slider", add a new meta value with the new key and delete the old meta
			if ( $slider_post_ids->have_posts() ) {
				foreach ( $slider_post_ids->get_posts() as $post_id ) {
					if ( add_post_meta( $post_id, ttf_get_foundry_slider()->meta_key, 1 ) )
						delete_post_meta( $post_id, 'watson_featured_slider', 1 );
				}
			}

			// Once everything is upgraded, let's warm the cache for good measure
			ttf_get_featured_slider_query( true );
		}

		// Increment the version
		update_option( $option_key, $current_version );
	}
endif;

add_filter( 'excerpt_length', 'watson_excerpt_length', 500 );

if ( ! function_exists( 'watson_excerpt_length' ) ) :
	/**
	 * Custom excerpt length
	 */
	function watson_excerpt_length( $length ) {
		if ( is_search() ) {
			return 50;
		} else {
			return 26;
		}
	}

endif; // watson_excerpt_length

add_filter( 'excerpt_more', 'watson_excerpt_more' );

if ( ! function_exists( 'watson_excerpt_more' ) ) :
	/**
	 * Custom excerpt more
	 */
	function watson_excerpt_more() {
		return '&hellip;';
	}

endif; // watson_excerpt_more

if ( ! function_exists( 'watson_post_thumbnail_caption' ) ) :
	/**
	 * Featured image caption
	 */
	function watson_post_thumbnail_caption() {
		global $post;

		$thumbnail_id    = get_post_thumbnail_id( $post->ID );
		$thumbnail_image = get_posts( array( 'p' => $thumbnail_id, 'post_type' => 'attachment' ) );

		if ( $thumbnail_image && isset( $thumbnail_image[0] ) ) {
			$excerpt = $thumbnail_image[0]->post_excerpt;
			$excerpt = apply_filters( 'watson_thumbnail_caption', $excerpt );
			echo '<figcaption>' . $excerpt . '</figcaption>';
		}
	}

endif; 

/** AJOUTE PAR MOI
 * Filter the except length to 20 words.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function wpdocs_custom_excerpt_length( $length ) {
		return 24;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

// watson_post_thumbnail_caption

// Add the same default filters as for the_excerpt
add_filter( 'watson_thumbnail_caption',     'wptexturize'      );
add_filter( 'watson_thumbnail_caption',     'convert_smilies'  );
add_filter( 'watson_thumbnail_caption',     'convert_chars'    );
add_filter( 'watson_thumbnail_caption',     'wpautop'          );
add_filter( 'watson_thumbnail_caption',     'shortcode_unautop');

if ( ! function_exists ( 'watson_comment' ) ) :

	function watson_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment; ?>
		<li  id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<article>
				<header class="comment-author vcard">
					<?php echo get_avatar( $comment, $size = '48' ); ?>
					<span class="fn"><?php comment_author_link(); ?></span>
					<time datetime="<?php comment_date(); ?>"><a href="<?php echo esc_url( get_comment_link() ); ?>"><?php comment_date(); ?></a></time>
				</header>

				<?php if ( $comment->comment_approved == '0' ) { ?>
					<p><?php _e( 'Your comment is awaiting moderation.', 'watson' ) ?></p>
				<?php } ?>

				<section class="comment post-content">
					<?php comment_text(); ?>
				</section>

				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>

			</article>
		</li>
	<?php
	}

endif; //watson_comment

if ( ! function_exists( 'ttf_common_archives_title' ) ) :
	/**
	 * Adjust the archives page title to something sensible
	 */
	function ttf_common_archives_title() {
		if ( is_category() ) { /* If this is a category archive */
			printf( __( 'Topo dans catégorie &#8216;%s&#8217; category', 'ttf_common' ), single_cat_title( '', false ) );
		} elseif ( is_tag() ) { /* If this is a tag archive */
			printf( __( 'Posts tagged &#8216;%s&#8217;', 'ttf_common' ), single_tag_title( '', false ) );
		} elseif ( is_day() ) { /* If this is a daily archive */
			printf( __( 'Archive for &#8216;%s&#8217;', 'ttf_common' ), get_the_time( 'F jS, Y' ) );
		} elseif ( is_month() ) { /* If this is a monthly archive */
			printf( __( 'Archive for &#8216;%s&#8217;', 'ttf_common' ), get_the_time( 'F, Y' ) );
		} elseif ( is_year() ) { /* If this is a yearly archive */
			printf( __( 'Archive for &#8216;%s&#8217;', 'ttf_common' ), get_the_time( 'Y' ) );
		} elseif ( is_author() ) { /* If this is an author archive */
			printf( __( 'Posts by %s', 'ttf_common' ), get_the_author() );
		} elseif ( is_paged() ) { /* If this is a paged archive */
			_e( 'Blog Archives', 'ttf_common' );
		}
	}

endif; // ttf_common_archives_title

add_action( 'wp_enqueue_scripts', 'watson_enqueue_scripts' );

if ( ! function_exists( 'watson_enqueue_scripts' ) ) :

	/**
	* Add theme styles and scripts here
	*/
	function watson_enqueue_scripts() {

		// Primary theme JavaScript
		wp_enqueue_script(
			'watson_javascript',
			get_template_directory_uri() . '/javascripts/theme.js',
			array( 'jquery' ),
			null
		);

		$protocol = is_ssl() ? 'https' : 'http';

		if ( ! is_admin() ) {
			// If web fonts are disabled, do not enqueue the web font CSS
			if ( ! watson_option( 'disable_web_font' ) ) {
				wp_enqueue_style(
					'watson-primary-font',
					"$protocol://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic,700italic"
				);

				wp_enqueue_style(
					'watson-secondary-font',
					"$protocol://fonts.googleapis.com/css?family=PT+Serif:400,700,400italic,700italic"
				);
			}

			wp_enqueue_style(
				'watson-style',
				get_bloginfo( 'stylesheet_url' )
			);
		}

		// Lastly, enqueue the comment reply script if required

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

endif; // watson_enqueue_scripts

if ( ! function_exists( 'watson_retina_logo_javascript' ) ) :
	/**
	 * JavaScript to smooth out the retina logo image loading
	 */
	function watson_retina_logo_javascript() { ?>
		<script type="text/javascript">
		(function($){
			// We don't know the height of the retina logo in advance, so we need to hide
			// it until it has loaded, and then we fade it in.
			var root = (typeof exports == 'undefined' ? window : exports);

			if (root.devicePixelRatio > 1) {
				var logoContainer = $('.branding');
				var logo = $('.branding .logo[data-retina-src]');
				logoContainer.css('min-height', '100px');
				logo.hide();

				var img = new Image();
				img.onload = function() {
					logo.attr('src', logo.attr('data-retina-src'));
					logo.removeAttr('data-retina-src');
					logo.css( 'max-height', 0.5 * this.height );
					logo.fadeIn(300);
					logoContainer.css('min-height', '');
				};

				img.src = logo.attr('data-retina-src');
			}
		})(jQuery);
		</script>
	<?php
	}

endif; // watson_retina_logo_javascript

add_action( 'customize_register', 'watson_hook_customizer_javascript' );

if ( ! function_exists( 'watson_hook_customizer_javascript' ) ) :
	/**
	 * Sets up the wp_footer hook to print the customizer setup JS.
	 */
	function watson_hook_customizer_javascript( $wp_customize ) {
		if ( $wp_customize->is_preview() && ! is_admin() ) {
			add_action( 'wp_footer', 'watson_print_customizer_javascript' );
		}
	}

endif; // watson_hook_customizer_javascript

if ( ! function_exists( 'watson_print_customizer_javascript' ) ) :
	/**
	 * Prints some JS variables that make customization previews much easier.
	 */
	function watson_print_customizer_javascript() {
		global $watson_color_selectors;
		?>
			<script type="text/javascript">
				var watsonFontColorSelectors = <?php echo json_encode( $watson_color_selectors['font'] ); ?>;
			</script>
		<?php
	}

endif; // watson_print_customizer_javascript
if ( ! function_exists( 'watson_ttf_foundry_slider_plugin_url' ) ) :

	/**
	 * Fix the path to the foundry slider JS.
	 *
	 * @param  string    $current_path    The current version of the path.
	 * @return string                     The modified path.
	 */
	function watson_ttf_foundry_slider_plugin_url( $current_path ) {
		return get_template_directory_uri() . '/functions/foundry-slider';
	}

endif;

add_filter( 'ttf_foundry_slider_plugin_url', 'watson_ttf_foundry_slider_plugin_url' );


/* utils */
function pre_var_dump($data) {
	echo '<pre>';
	var_dump($data);
	echo '</pre>';
	exit(0);
}

function spre_var_dump($data) {
	echo '<pre>';
	var_dump($data);
	echo '</pre>';
}

/* Renommage de Posts en Topos dans le menu d'administration */
add_action( 'admin_menu', 'change_admin_menu_topos' );
function change_admin_menu_topos(){
	global $menu, $submenu;

	if ($menu[5][0] === 'Articles') {
		$menu[5][0] = 'Topos';
	}
}



/* Climbing7 init */
function climbing7_init() {
	// En dessous : rafraichir les regles d'URL si pb avec erreur 404 d'url no exist (annule le cache)
	// global $wp_rewrite;
	// $wp_rewrite->flush_rules();

	/* Renommage de `Posts` en `Topos` */
	global $wp_post_types;
	$post_labels = &$wp_post_types['post']->labels;
	$post_labels->name = 'Topos';
	$post_labels->singular_name = 'Topo';
	$post_labels->add_new_item = 'Ajouter un nouveau Topo';
	$post_labels->edit_item = 'Éditer le Topo';
	$post_labels->new_item = 'Nouveau Topo';
	$post_labels->view_item = 'Voir le Topo';
	$post_labels->view_items = 'Voir les Topos';
	$post_labels->search_items = 'Rechercher des Topos';
	$post_labels->not_found = 'Aucun Topos trouvés';
	$post_labels->not_found_in_trash = 'Aucun Topos trouvés dans la corbeille';
	$post_labels->all_items = 'Tous les Topos';
	$post_labels->archives = 'Archive des Topos';
	$post_labels->attributes = 'Attributs du Topo';
	$post_labels->insert_into_item = 'Insérer dans le Topo';
	$post_labels->uploaded_to_this_item = 'Téléversé dans ce Topo';
	$post_labels->filter_items_list = 'Filtrer la liste des Topos';
	$post_labels->items_list_navigation = 'Navigation de la liste des Topos';
	$post_labels->items_list = 'Liste des Topos';
	$post_labels->item_published = 'Topo publié.';
	$post_labels->item_published_privately = 'Topo publié en privé.';
	$post_labels->item_reverted_to_draft = 'Topo transformé en brouillon.';
	$post_labels->item_scheduled = 'Topo planifié.';
	$post_labels->item_updated = 'Topo mis à jour.';

	
	/* Voyages post type */
	register_post_type(
		'voyage',
		array(
			'has_archive' => true,
			'menu_icon' => 'dashicons-admin-site-alt2',
			'labels' => array(
				'name' => 'Voyages',
				'singular_name' => 'Voyage',
				'add_new_item' => 'Ajouter un Voyage',
				'edit_item' => 'Éditer le voyage',
				'new_item' => 'Nouveau Voyage',
				'view_item' => 'Voir le Voyage',
				'search_items' => 'Rechercher des Voyages',
				'not_found' => 'Aucun Voyages trouvés',
				'not_found_in_trash' => 'Aucun Voyages trouvés dans la corbeille',
				'all_items' => 'Tous les Voyages',
				'archives' => 'Archive des Voyages',
				'attributes' => 'Attributs du Voyage',
				'insert_into_item' => 'Insérer dans le Voyage',
				'uploaded_to_this_item' => 'Téléversé dans ce Voyage',
				'filter_items_list' => 'Filtrer la liste des Voyages',
				'items_list_navigation' => 'Navigation de la liste des Voyages',
				'items_list' => 'Liste des Voyages',
				'item_published' => 'Voyage publié.',
				'item_published_privately' => 'Voyage publié en privé.',
				'item_reverted_to_draft' => 'Voyage transformé en brouillon.',
				'item_scheduled' => 'Voyage planifié.',
				'item_updated' => 'Voyage mis à jour.',
			),
			'description' => 'Mes voyages',
			'public' => true,
			'menu_position' => 6,
			'show_in_rest' => true,
		),
	);

	/*
	 * Taxonomies personalisées
	 */
	/* Regions */
	// Les labels de la taxonomie Région
	$lieux_tax_labels = [
		'name'              => 'Lieux',
		'singular_name'     => 'Lieu',
		'search_items'      => 'Rechercher les lieux',
		'all_items'         => 'Toutes les lieux',
		'parent_item'       => 'Lieu parent',
		'parent_item_colon' => 'Lieu parent :',
		'edit_item'         => 'Éditer le lieu',
		'update_item'       => 'Mettre à jour le lieu',
		'add_new_item'      => 'Ajouter un nouveau lieu',
	];

	$lieux_tax_args = [
		'hierarchical'      => true, // make it hierarchical (like categories)
		'labels'            => $lieux_tax_labels,
		'public'            => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
	];

	register_taxonomy('lieux', ['post', 'voyage'], $lieux_tax_args);

	/* Activités */
	// Les labels de la taxonomie Activité
	$activity_tax_labels = [
		'name'              => 'Activités',
		'singular_name'     => 'Activité',
		'search_items'      => 'Rechercher les activités',
		'all_items'         => 'Toutes les activités',
		'edit_item'         => 'Éditer l\'activité',
		'update_item'       => 'Mettre à jour l\'activité',
		'add_new_item'      => 'Ajouter une nouvelle activité',
	];

	$activity_tax_args = [
		'labels'            => $activity_tax_labels,
		'public'            => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
	];

	register_taxonomy('activites', ['post', 'voyage'], $activity_tax_args);
}
add_action('init', 'climbing7_init');

/*
 * Rajout post type Voyage dans la query home
 */
function ajout_voyage_main_query($query) {
	if (!is_admin() && $query->is_main_query() && $query->is_home) {
		$query->set('post_type', array('post', 'voyage'));
	}
}
add_action( 'pre_get_posts', 'ajout_voyage_main_query' );

/*
 * Ajout d'un filtrage par activité/région
 * sur la requête des posts principale
 */
function filtrage_posts_par_activites_et_lieux($query) {
	if (
		$query->is_main_query() // si c'est la requete des posts principale  
		&& (is_search() || is_archive()) // et, si on est sur la page de recherche ou d'archive
		&& (isset($_GET['f_activites']) || isset($_GET['f_lieux'])) // et, si le paramètre d'URL "activite" ou "lieu" existe
	) {
		$activite_query_et_relation = null;
		$lieux_query_et_relation = null;

		// Si demandé,
		// On ajoute un filtrage via la taxonomie "Activité"
		if (isset($_GET['f_activites'])) {
			$activites_demandees = $_GET['f_activites'];

			// Si c'est pas une liste, on transforme la valeur en liste à 1 élèment
			if (!is_array($activites_demandees)) {
				$activites_demandees = array(
					$activites_demandees
				);
			}

			// Construction de la requête
			$activite_query = array_map(
				function ($activite) {
					return [
						'taxonomy' => 'activites',
						'field' => 'slug',
						'terms' => $activite,
					];
				},
				$activites_demandees,
			);

			$activite_query_et_relation = array_merge(
				$activite_query,
				[
					'relation' => 'OR',
				],
			);
		}

		// Si demandé,
		// On ajoute un filtrage via la taxonomie "Région"
		if (isset($_GET['f_lieux'])) {
			$lieux_demandes = $_GET['f_lieux'];

			// Si c'est pas une liste, on transforme la valeur en liste à 1 élèment
			if (!is_array($lieux_demandes)) {
				$lieux_demandes = [$lieux_demandes];
			}

			$lieux_query = array_map(
				function ($lieu) {
					return [
						'taxonomy' => 'lieux',
						'field' => 'slug',
						'terms' => $lieu,
					];
				},
				$lieux_demandes,
			);

			$lieux_query_et_relation = array_merge(
				$lieux_query,
				[
					'relation' => 'OR',
				],
			);
		}

		// Construction de la requete des taxonomies finale

		if (isset($query->tax_query)) {
			$tax_query = $query->tax_query->queries;
			$tax_query['relation'] = 'AND';
		} else {
			$tax_query = [
				'relation' => 'AND',
			];
		}

		if ($activite_query_et_relation) {
			$tax_query[] = $activite_query_et_relation;
		}

		if ($lieux_query_et_relation) {
			$tax_query[] = $lieux_query_et_relation;
		}

		// echo'lol';
		// pre_var_dump($tax_query);

		$query->set(
			'tax_query',
			$tax_query
		);
	}
}
add_action( 'pre_get_posts', 'filtrage_posts_par_activites_et_lieux' );

/*
 * Nombre de posts affichés
 */
add_action( 'pre_get_posts', 'nombre_de_posts_affiches' );
function nombre_de_posts_affiches($query) {
	if (!is_admin() && $query->is_main_query()) {
		// Si on n'est pas dans l'administration,
		// Et que c'est la requete principale

		if ($query->is_home) {
			$query->set('posts_per_page', 5);
		} else if ($query->is_archive) {
			$query->set('posts_per_page', 12);
		}
	}
}