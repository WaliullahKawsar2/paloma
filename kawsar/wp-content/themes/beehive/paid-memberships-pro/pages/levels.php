<?php
/**
 * The template for displaying pm pro levels loop
 *
 * @see     https://www.paidmembershipspro.com/new-method-load-custom-templates-pmpro-generated-pages-system-generated-emails/
 * @package paid-membership-pro/pages
 */

defined( 'ABSPATH' ) || exit;

global $wpdb, $pmpro_msg, $pmpro_msgt, $current_user;

$pmpro_levels      = pmpro_getAllLevels( false, true );
$pmpro_level_order = pmpro_getOption( 'level_order' );

if ( ! empty( $pmpro_level_order ) ) {
	$order = explode( ',', $pmpro_level_order );

	// reorder array.
	$reordered_levels = array();
	foreach ( $order as $level_id ) {
		foreach ( $pmpro_levels as $key => $level ) {
			if ( $level_id == $level->id ) {
				$reordered_levels[] = $pmpro_levels[ $key ];
			}
		}
	}

	$pmpro_levels = $reordered_levels;
}

$pmpro_levels = apply_filters( 'pmpro_levels_array', $pmpro_levels );

// Beehive classes.
if ( in_array( beehive()->layout->get(), array( 'left', 'right', 'social' ), true ) ) {
	$max_columns = 3;
} else {
	$max_columns = 4;
}

if ( $pmpro_msg ) {
	?>
<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_message ' . $pmpro_msgt, $pmpro_msgt ) ); ?>"><?php echo wp_kses_data( $pmpro_msg ); ?></div>
	<?php
}
?>

<div id="pmpro_levels_table" class="pmpro-levels-container<?php echo ( ! empty( $pmpro_levels ) && is_array( $pmpro_levels ) && count( $pmpro_levels ) > 0 ) ? esc_attr( ' level-count-' . count( $pmpro_levels ) ) : esc_attr( '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
	<div class="<?php echo esc_attr( apply_filters( 'beehive_membership_block_classes', 'beehive-memberships max-columns-' . $max_columns ) ); ?>">
		<?php foreach ( $pmpro_levels as $level ) : ?>
			<?php
			if ( isset( $current_user->membership_level->ID ) ) {
				$current_level = ( $current_user->membership_level->ID == $level->id );
			} else {
				$current_level = false;
			}
			?>
			<div class="column">
				<div class="level-wrapper
				<?php
				if ( $current_level == $level ) {
					echo ' active';}
				?>
				">
					<?php $level_title = $current_level ? "<strong>{$level->name}</strong>" : $level->name; ?>
					<h4 class="level-title">
						<?php echo wp_kses_post( $level_title ); ?>
					</h4>
					<div class="level-description">
						<?php if ( $level->description ) : ?>
							<?php echo wp_kses_post( $level->description ); ?>
						<?php endif; ?>
					</div>
					<?php
					if ( pmpro_isLevelFree( $level ) ) {
						$cost_text = '<strong>' . esc_html__( 'Free', 'paid-memberships-pro' ) . '</strong>';
					} else {
						$cost_text = pmpro_getLevelCost( $level, true, true );
						if ( pmpro_formatPrice( $level->initial_payment ) == pmpro_formatPrice( $level->billing_amount ) ) {
							$cost_text = preg_replace( '/' . pmpro_formatPrice( $level->initial_payment ) . '/', '', $cost_text, 1 );
							$cost_text = '<strong>' . pmpro_formatPrice( $level->initial_payment ) . '</strong>' . wp_strip_all_tags( $cost_text );
						}
					}
					?>
					<?php if ( ! empty( $cost_text ) ) : ?>
						<div class="level-pricing">
							<?php echo wp_kses_post( $cost_text ); ?>
						</div>
					<?php endif; ?>
					<?php $expiration_text = pmpro_getLevelExpiration( $level ); ?>
					<?php if ( ! empty( $expiration_text ) ) : ?>
						<p class="level-expiration mute"><?php echo wp_kses_post( $expiration_text ); ?></p>
					<?php endif; ?>
					<div class="level-checkout">
						<?php if ( empty( $current_user->membership_level->ID ) ) : ?>
							<a class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_btn pmpro_btn-select', 'pmpro_btn-select' ) ); ?>" href="<?php echo esc_url( pmpro_url( 'checkout', '?level=' . $level->id, 'https' ) ); ?>"><?php esc_html_e( 'Select', 'paid-memberships-pro' ); ?></a>
						<?php elseif ( ! $current_level ) : ?>
							<a class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_btn pmpro_btn-select', 'pmpro_btn-select' ) ); ?>" href="<?php echo esc_url( pmpro_url( 'checkout', '?level=' . $level->id, 'https' ) ); ?>"><?php esc_html_e( 'Select', 'paid-memberships-pro' ); ?></a>
						<?php elseif ( $current_level ) : ?>
							<?php if ( pmpro_isLevelExpiringSoon( $current_user->membership_level ) && $current_user->membership_level->allow_signups ) : ?>
								<a class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_btn pmpro_btn-select', 'pmpro_btn-select' ) ); ?>" href="<?php echo esc_url( pmpro_url( 'checkout', '?level=' . $level->id, 'https' ) ); ?>"><?php esc_html_e( 'Renew', 'paid-memberships-pro' ); ?></a>
							<?php else : ?>
								<a class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_btn disabled', 'pmpro_btn' ) ); ?>" href="<?php echo esc_url( pmpro_url( 'account' ) ); ?>"><?php esc_html_e( 'Your&nbsp;Level', 'paid-memberships-pro' ); ?></a>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<p class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_actions_nav' ) ); ?>">
	<?php if ( ! empty( $current_user->membership_level->ID ) ) { ?>
		<a href="<?php echo esc_url( pmpro_url( 'account' ) ); ?>" id="pmpro_levels-return-account"><?php esc_html_e( '&larr; Return to Your Account', 'paid-memberships-pro' ); ?></a>
	<?php } else { ?>
		<a href="<?php echo esc_html( home_url( '/' ) ); ?>" id="pmpro_levels-return-home"><?php esc_html_e( '&larr; Return to Home', 'paid-memberships-pro' ); ?></a>
	<?php } ?>
</p> <!-- end pmpro_actions_nav -->
