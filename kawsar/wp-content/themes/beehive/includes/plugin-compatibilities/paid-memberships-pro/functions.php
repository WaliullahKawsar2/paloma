<?php
/**
 * PM_pro functions
 *
 * Functions that will make PM_pro compatible
 * with beehive theme
 *
 * @package WordPress
 * @subpackage beehive
 * @since 1.3.5
 */

/** Do not allow directly accessing this file. */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Include PM_pro
 * compatability class
 *
 * @since 1.3.5
 */
require_once BEEHIVE_INC . '/plugin-compatibilities/paid-memberships-pro/class-beehive-pmpro.php';

/**
 * Actions
 */
add_action( 'beehive_before_page_entry_content', 'beehive_pmpro_page_titles', 10 );
add_action( 'beehive_before_page_entry_content', 'beehive_pmpro_checkout_display_custom_notice', 20 );
add_action( 'template_redirect', 'beehive_pmpro_redirect_checkout_page' );

/**
 * Functions and definations starts
 */
if ( ! function_exists( 'beehive_pmpro_is_page' ) ) :
	/**
	 * Check for a PM Pro page.
	 *
	 * @param array $page page slug.
	 * @return array
	 * @since 1.3.5
	 */
	function beehive_pmpro_is_page( $page ) {
		global $pmpro_pages;
		if ( isset( $pmpro_pages[ $page ] ) && ! empty( $pmpro_pages[ $page ] ) ) {
			return is_page( $pmpro_pages[ $page ] );
		}
		return false;
	}
endif;

if ( ! function_exists( 'beehive_pmpro_page_titles' ) ) :
	/**
	 * Print page title contitionally
	 *
	 * @return void
	 * @since 1.3.5
	 */
	function beehive_pmpro_page_titles() {

		// Check if pmpro page.
		if ( TH_Helpers::is_pmpro() && in_array( beehive()->layout->get(), array( 'social', 'social-12', 'social-collapsed' ), true ) ) {

			// Retunr early.
			if ( '0' === beehive()->options->get_meta_option( 'page-title' ) ) {
				return;
			}

			// Billing page title.
			if ( beehive_pmpro_is_page( 'billing' ) ) {
				$page_title = __( 'Membership Billing', 'paid-memberships-pro' );
			}

			// Cancel page title.
			if ( beehive_pmpro_is_page( 'cancel' ) && ! empty( $_REQUEST['levelstocancel'] ) ) {
				$page_title = __( 'Membership Cancel', 'paid-memberships-pro' );
			}

			// Checkout page title.
			if ( beehive_pmpro_is_page( 'checkout' ) ) {
				$page_title = __( 'Membership Checkout', 'paid-memberships-pro' );
			}

			// Confirmation page title.
			if ( beehive_pmpro_is_page( 'confirmation' ) ) {
				$page_title = __( 'Membership Confirmation', 'paid-memberships-pro' );
			}

			// Invoice page title.
			if ( beehive_pmpro_is_page( 'invoice' ) && empty( $_REQUEST['invoice'] ) ) {
				$page_title = __( 'Membership Invoice', 'paid-memberships-pro' );
			}

			// Login page title.
			if ( ! is_user_logged_in() && beehive_pmpro_is_page( 'login' ) ) {
				if ( ! empty( $_REQUEST['action'] ) && 'reset_pass' === $_REQUEST['action'] ) {
					$page_title = __( 'Lost Password', 'paid-memberships-pro' );
				} elseif ( ! empty( $_REQUEST['action'] ) && 'rp' === $_REQUEST['action'] ) {
					$page_title = __( 'Reset Password', 'paid-memberships-pro' );
				} elseif ( empty( $_REQUEST['action'] ) ) {
					$page_title = __( 'Log In', 'paid-memberships-pro' );
				}
			}

			// Membership levels page title.
			if ( beehive_pmpro_is_page( 'levels' ) ) {
				$page_title = __( 'Membership Levels', 'paid-memberships-pro' );
			}

			// Manage profile edit page title.
			if ( beehive_pmpro_is_page( 'member_profile_edit' ) && empty( $_REQUEST['view'] ) ) {
				$page_title = __( 'Your Profile', 'paid-memberships-pro' );
			}

			// Print the title.
			if ( isset( $page_title ) && ! empty( $page_title ) ) {
				printf( '<h2 class="pm-pro-page-title h3">%s</h2>', esc_html( $page_title ) );
			}
		}
	}
endif;

if ( ! function_exists( 'beehive_pmpro_checkout_display_custom_notice' ) ) {
	/**
	 * Display custom notice on pm pro checkout page.
	 *
	 * @return void
	 * @since 1.3.5
	 */
	function beehive_pmpro_checkout_display_custom_notice() {
		if ( beehive_pmpro_is_page( 'checkout' ) ) {
			global $pmpro_msg;
			if ( empty( $pmpro_msg ) ) {
				printf( '<div class="%s">%s</div>', esc_attr( pmpro_get_element_class( 'pmpro_message' ) ), esc_html__( 'Please complete all required fields.', 'paid-memberships-pro' ) );
			}
		}
	}
}

if ( 'beehive_pmpro_redirect_checkout_page' ) :
	/**
	 * Redirect checkout page if registration are not allowed
	 *
	 * @return void
	 * @since 1.3.5
	 */
	function beehive_pmpro_redirect_checkout_page() {
		if ( ! get_option( 'users_can_register' ) && ! is_user_logged_in() && ( function_exists( 'pmpro_is_checkout' ) && pmpro_is_checkout() ) ) {
			wp_safe_redirect( esc_url( home_url( '/' ) ) );
			exit;
		}
	}
endif;

/**
 * Functions, hooks and fitlers for PM pro addons.
 */
if ( function_exists( 'pmpro_bp_buddypress_or_pmpro_registration' ) ) :
	remove_action( 'template_redirect', 'pmpro_bp_buddypress_or_pmpro_registration', 70 );
	add_action( 'template_redirect', 'beehive_pmpro_bp_buddypress_or_pmpro_registration', 70 );
endif;

// PM pro addons functions.
if ( function_exists( 'pmpro_bp_buddypress_or_pmpro_registration' ) ) :
	/**
	 * Redirect users on registration depending on the options set.
	 *
	 * @return void
	 * @since 1.3.5
	 */
	function beehive_pmpro_bp_buddypress_or_pmpro_registration() {
		global $post, $pmpro_pages;
		// If BP or PMPro are not active, ignore.
		if ( ! function_exists( 'bp_is_register_page' ) || ! function_exists( 'pmpro_url' ) ) {
			return;
		}
		$bp_pages          = get_option( 'bp-pages' );
		$pmpro_bp_register = get_option( 'pmpro_bp_registration_page' );
		if ( ! empty( $pmpro_bp_register ) && $pmpro_bp_register == 'buddypress' && ( isset( $post->ID ) && $post->ID != 0 ) && ( isset( $post->ID ) && $post->ID == $pmpro_pages['levels'] ) && ! is_user_logged_in() ) { // @codingStandardsIgnoreLine
			// Use BuddyPress Register page.
			wp_safe_redirect( get_permalink( $bp_pages['register'] ) );
			exit;
		} elseif ( ! empty( $pmpro_bp_register ) && $pmpro_bp_register == 'pmpro' && bp_is_register_page() && ( isset( $post->ID ) && $post->ID != $pmpro_pages['levels'] ) ) { // @codingStandardsIgnoreLine
			// use PMPro Levels page.
			$url = pmpro_url( 'levels' );
			wp_safe_redirect( $url );
			exit;
		}
	}
endif;
