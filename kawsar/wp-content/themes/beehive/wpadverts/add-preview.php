<?php
/** Do not allow directly accessing this file. */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' ); } ?>

<div class="advert_listing_preview_title">
	<h4 class="preview-title"><?php esc_html_e( 'Preview', 'beehive' ); ?></h4>
	<form action="" method="post">
		<input type="hidden" name="_adverts_action" value="" />
		<input type="hidden" name="_post_id" id="_post_id" value="<?php echo esc_attr( $post_id ); ?>" />
		<input type="hidden" name="_post_id_nonce" id="_post_id_nonce" value="<?php echo esc_attr($post_id_nonce) ?>" />
		<input type="submit" value="<?php esc_attr_e( 'Edit Listing', 'wpadverts' ); ?>" class="adverts-cancel-unload edit button-solid" />
	</form>
	<form action="" method="post">
		<input type="hidden" name="_adverts_action" value="save" />
		<input type="hidden" name="_post_id" id="_post_id" value="<?php echo esc_attr( $post_id ); ?>" />
		<input type="hidden" name="_post_id_nonce" id="_post_id_nonce" value="<?php echo esc_attr($post_id_nonce) ?>" />
		<input type="submit" value="<?php esc_attr_e( 'Publish Listing', 'wpadverts' ); ?>" class="adverts-cancel-unload publish button-solid" />
	</form>
</div>

<?php require apply_filters( 'adverts_template_load', ADVERTS_PATH . 'templates/single.php' ); ?>
