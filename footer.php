<?php
/**
 * @package Watson
 */
?>
	<footer role="contentinfo">
		<?php $one_sidebar_is_active = is_active_sidebar( 'footer_1' ) || is_active_sidebar( 'footer_2' ); ?>
		<?php if ( ! is_single() && ! is_attachment() && $one_sidebar_is_active ) : ?>
			<div class="footer-container">
				<?php if ( is_active_sidebar( 'footer_1' ) ): ?>
					<section class="footer-1">
						<?php dynamic_sidebar( 'footer_1' ) ?>
					</section>
				<?php endif; ?>
				<?php if ( is_active_sidebar( 'footer_2' ) ): ?>
					<section class="footer-2">
						<?php dynamic_sidebar( 'footer_2' ) ?>
					</section>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if ( ! watson_option( 'hide_icons' ) ) : ?>
			<nav class="social">
				<ul>
					<?php if ( $twitter_url = watson_option( 'twitter_url' ) ) : ?>
						<li class="twitter">
							<a href="<?php echo esc_url( $twitter_url ); ?>" title="<?php esc_attr_e( 'Twitter', 'watson' ); ?>"></a>
						</li>
					<?php endif; ?>
					<?php if ( $facebook_url = watson_option( 'facebook_url' ) ) : ?>
						<li class="facebook">
							<a href="<?php echo esc_url( $facebook_url ); ?>" title="<?php esc_attr_e( 'Facebook', 'watson' ); ?>"></a>
						</li>
					<?php endif; ?>
					<?php if ( $google_url = watson_option( 'google_url' ) ) : ?>
						<li class="google">
							<a href="<?php echo esc_url( $google_url ); ?>" title="<?php esc_attr_e( 'Google Plus', 'watson' ); ?>"></a>
						</li>
					<?php endif; ?>
					<?php if ( $flickr_url = watson_option( 'flickr_url' ) ) : ?>
						<li class="flickr">
							<a href="<?php echo esc_url( $flickr_url ); ?>" title="<?php esc_attr_e( 'Flickr', 'watson' ); ?>"></a>
						</li>
					<?php endif; ?>
					<li class="rss">
						<a href="<?php echo esc_url ( get_bloginfo( 'rss_url' ) ); ?>" title="<?php esc_attr_e( 'RSS feed', 'watson' ); ?>"></a>
					</li>
				</ul>
			</nav>
		<?php endif; ?>
		<?php if ( $copy_text = watson_option( 'copyright_text' ) ) : ?>
			<p class="credit-line">
				<?php
					$copy_text =
						wp_kses( $copy_text, array(
							'a' => array( 'href' => array(), 'title' => array() ),
							'br' => array(),
							'em' => array(),
							'img' => array( 'src' => array(), 'alt' => array() ),
							'strong' => array() ) );
				?>
				<?php echo $copy_text; ?>
			</p>
		<?php endif; ?>

		<p class="byline">
				<a title="<?php esc_attr_e( 'Theme info', 'watson' ); ?>" href="https://thethemefoundry.com/wordpress-themes/watson/">Watson theme</a>
				<span>by</span> <a title="<?php esc_attr_e( 'The Theme Foundry home page', 'watson' ); ?>" href="https://thethemefoundry.com/">The Theme Foundry</a>
				<br />
				Édité par Steph. & Ilyes
		</p>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>