<?php
/**
 * @package Watson
 */
?>
<!DOCTYPE html>
<!--[if IE 7]>    <html class="no-js IE7 IE" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js IE8 IE" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>    <html class="no-js IE9 IE" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<title><?php wp_title( '' ); ?></title>
	<!-- Basic Meta Data -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<!-- WordPress -->
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="container">
	<header role="banner">
		<div class="branding">
			<?php if ( $logo_url = watson_option( 'logo_url' ) ) : ?>
				<?php
					$retina_logo_url = watson_option( 'hi_res_logo_image' );

					$retina_data = $retina_logo_url ? ' data-retina-src="' . esc_attr( $retina_logo_url ) . '"' : '';
				?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img class="logo" src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) );?>" title="<?php _e( 'Home', 'watson' ); ?>"<?php echo $retina_data; ?>>
				</a>
				<?php if ( $retina_logo_url ) : ?>
					<?php watson_retina_logo_javascript(); ?>
				<?php endif; ?>
				<?php else : ?>
				<h1>
					<a title="<?php esc_attr_e( 'Home', 'watson' ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php bloginfo( 'name' ); ?>
					</a>
				</h1>
			<?php endif; ?>
		</div>
		<?php if ( get_bloginfo( 'description' ) ) : ?>
			<h4 class="tagline"><?php bloginfo( 'description' ); ?></h4>
		<?php endif; ?>
		<nav role="navigation">
			<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'primary',
						'container_class' => 'clear',
						'menu_class'      => 'nav',
						'depth'           => '2'
					)
				);
			?>
		</nav>
	</header>