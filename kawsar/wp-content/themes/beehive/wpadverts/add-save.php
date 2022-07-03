<?php
/** Do not allow directly accessing this file. */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' ); } ?>

<?php adverts_flash( $adverts_flash ); ?>

<div>
	<?php if ( '1' == $moderate ) : ?>
		<p>
			<?php esc_html_e( 'Your ad has been put into moderation, please wait for admin to approve it.', 'wpadverts' ); ?>
		</p>
	<?php else : ?>
		<p>
			<p>
				<?php
					// translators: ad publish message.
					printf( wp_kses_post( __( 'Your ad has been published. You can view it here "<a href="%1$s" class="color-primary">%2$s</a>".', 'wpadverts' ) ), esc_url( get_post_permalink( $post_id ) ), get_post( $post_id )->post_title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</p>
		</p>
	<?php endif; ?>
</div>
