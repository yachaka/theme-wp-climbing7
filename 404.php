<?php
/**
 * @package Watson
 */
?>
<?php get_header(); ?>
<div role="main">
	<h1 class="heading">
		<?php _e( '404: Page Not Found', 'watson' ); ?>
	</h1>
	<div>
		<p><?php _e( 'We are terribly sorry, but the <abbr title="Universal resource locator">URL</abbr> you typed <em>no longer exists</em>. It might have been moved or deleted.', 'watson' ); ?></p>
		<?php get_search_form(); ?>
	</div>
</div>
<?php get_footer(); ?>