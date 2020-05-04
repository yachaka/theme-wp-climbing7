<?php
/**
 * @package Watson
 */
?>
<aside role="complementary">
	<?php if ( ! dynamic_sidebar( 'primary_sidebar' ) ) : ?>
		<aside class="widget widget_text">
			<h3 class="widgettitle"><?php _e( 'Temporary sidebar', 'watson' ); ?></h3>
			<div class="textwidget">
				<p><?php _e( 'Start adding your own Widgets to the sidebar by going to the <em>Appearance &rarr; Widgets</em> screen on your admin dashboard. This sidebar cannot be toggled off or removed.', 'watson' ); ?></p>
			</div>
		</aside>
	<?php endif; ?>
</aside>