<?php
/**
 * @package Watson
 */
?>
<form method="get" role="search"  action="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Type and press Enter to search', 'watson' ); ?>">
	<input
		type="text"
		id="s"
		name="s"
		size="34"
		value="<?php esc_attr_e( 'Search site&hellip;', 'watson' ) ?>"
		onfocus="if (this.value == '<?php esc_attr_e( 'Search site&hellip;', 'watson' ) ?>') { this.value = ''; }"
		onblur="if (this.value == '') this.value='<?php esc_attr_e( 'Search site&hellip;', 'watson' ) ?>';"
	/>
</form>