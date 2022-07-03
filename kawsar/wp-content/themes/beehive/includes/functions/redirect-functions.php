<?php
/**
 * Redirect Functions
 *
 * Functions for user redirections on various instances.
 *
 * @package WordPress
 * @subpackage beehive
 * @since 1.2.6
 */

/** Do not allow directly accessing this file. */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

// Actions
// Redirect to home after logout.
add_action( 'wp_logout', 'beehive_redirect_user_after_logout' );
// Redirect logged in users from home.
add_action( 'template_redirect', 'beehive_redirect_logged_in_users_from_homepage' );
// Guest user redirection.
add_action( 'template_redirect', 'beehive_guest_user_restriction' );

// Functions and definations.
if ( ! function_exists( 'beehive_redirect_to_home' ) ) :
	/**
	 * Redirect to home
	 *
	 * @return void
	 * @since 1.0.6
	 */
	function beehive_redirect_to_home() {
		wp_safe_redirect( esc_url( home_url( '/' ) ) );
		exit();
	}
endif;

if ( ! function_exists( 'beehive_get_after_login_redirect_page_url' ) ) :
	/**
	 * Redirect user after logging in via the ajax login form.
	 * No longer in use.
	 *
	 * @return string/false
	 * @since 1.2.6
	 */
	function beehive_get_after_login_redirect_page_url() {
		$redirect_page_id = beehive()->options->get( 'key=login-redirect' );
		if ( ! empty( $redirect_page_id ) ) {
			return get_the_permalink( $redirect_page_id );
		} else {
			if ( beehive()->options->get( 'key=activity-login-redirect' ) && ( function_exists( 'bp_is_active' ) && beehive_bp_get_bp_page_permalink() ) ) {
				return beehive_bp_get_bp_page_permalink();
			}
		}
		return false;
	}
endif;

if ( ! function_exists( 'beehive_redirect_logged_in_users_from_homepage' ) ) :
	/**
	 * Redirect logged in users from home.
	 *
	 * @return string/false
	 * @since 1.2.6
	 */
	function beehive_redirect_logged_in_users_from_homepage() {
		if ( ! is_user_logged_in() || current_user_can( 'manage_options' ) || is_home() ) {
			return;
		}
		$redirect_page_id = beehive()->options->get( 'key=home-redirect' );
		if ( ! empty( $redirect_page_id ) ) {
			if ( get_option( 'page_on_front' ) === $redirect_page_id ) {
				return;
			}
			if ( is_front_page() && ( false == ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( 'xmlhttprequest' == strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) ) ) ) {
				wp_safe_redirect( esc_url( get_the_permalink( $redirect_page_id ) ) );
				exit();
			}
		} else {
			if ( function_exists( 'bp_is_active' ) ) {
				beehive_bp_redirect_user_to_activity();
			}
		}
	}
endif;

if ( ! function_exists( 'beehive_redirect_user_after_logout' ) ) :
	/**
	 * Redirect to home
	 *
	 * @return void
	 * @since 1.2.6
	 */
	function beehive_redirect_user_after_logout() {

		// Prevent double redirection.
		if ( beehive()->options->get( 'key=redirect-page&meta=1&options=0' ) || beehive()->options->get( 'key=guest-redirect-to' ) ) {
			return;
		}

		// Redirect to home.
		if ( beehive()->options->get( 'key=logout-to-home' ) ) {
			beehive_redirect_to_home();
		}
	}
endif;

if ( ! function_exists( 'beehive_guest_user_restriction' ) ) {
	/**
	 * Redirect guest users.
	 *
	 * @return mixed
	 * @since 1.2.0
	 */
	function beehive_guest_user_restriction() {
		if ( ! is_user_logged_in() ) {

			// Return if page has redirection turned off.
			if ( 'no' === beehive()->options->get( 'key=guest-redirection&meta=1&options=0' ) ) {
				return;
			}

			// Do page specific redirection.
			if ( ! empty( beehive()->options->get( 'key=redirect-page&meta=1&options=0' ) ) ) {
				$post_redirect_id  = beehive()->options->get( 'key=redirect-page&meta=1&options=0' );
				$post_redirect_url = get_the_permalink( $post_redirect_id );
				if ( ! empty( $post_redirect_url ) && get_the_ID() != $post_redirect_id ) { // @codingStandardsIgnoreLine
					wp_safe_redirect( esc_url( $post_redirect_url ) );
					exit();
				}
			}

			// Redirect page id.
			$redirect_page_id = beehive()->options->get( 'key=guest-redirect-to' );
			if ( empty( $redirect_page_id ) ) {
				return;
			}

			// Redirect page url.
			$redirect_page_url = get_the_permalink( $redirect_page_id );
			if ( empty( $redirect_page_url ) ) {
				return;
			}

			// Return if it's user registration or activation page or lost password page.
			if ( ( function_exists( 'bp_is_active' ) && ( bp_is_register_page() || bp_is_activation_page() ) ) || ( class_exists( 'WooCommerce' ) && is_account_page() ) ) {
				return;
			}

			// Return if pm pro login of checkout page.
			if ( function_exists( 'pmpro_is_plugin_active' ) && ( beehive_pmpro_is_page( 'levels' ) || pmpro_is_checkout() || pmpro_is_login_page() ) ) {
				return;
			}

			// Current page id.
			if ( function_exists( 'bp_is_active' ) && bp_is_directory() ) {
				$current_page_id = get_option( 'bp-pages' )[ bp_current_component() ];
			} else {
				$current_page_id = get_the_ID();
			}

			// Return if landed.
			if ( $current_page_id == $redirect_page_id ) { // @codingStandardsIgnoreLine
				return;
			}

			// Redirect.
			if ( 'advanced' === beehive()->options->get( 'key=guest-redirect-option' ) || '0' === beehive()->options->get( 'key=restrict-everything' ) ) {

				// Restrict by components.
				$restrict_by_component = beehive()->options->get( 'key=restrict-by-feature' );

				// Blog.
				if ( ( ( isset( $restrict_by_component['blog'] ) && '1' === $restrict_by_component['blog'] ) || beehive()->options->get( 'key=guest-blog-redirection' ) ) && get_post_type() === 'post' ) {
					wp_safe_redirect( esc_url( $redirect_page_url ) );
					exit();
				}
				// Buddypress components.
				if ( ( ( isset( $restrict_by_component['bp'] ) && '1' === $restrict_by_component['bp'] ) || beehive()->options->get( 'key=guest-bp-redirection' ) ) && TH_Helpers::is_buddypress() ) {
					wp_safe_redirect( esc_url( $redirect_page_url ) );
					exit();
				}
				// Media pages.
				if ( ( ( isset( $restrict_by_component['rtm'] ) && '1' === $restrict_by_component['rtm'] ) || beehive()->options->get( 'key=guest-bp-redirection' ) ) && TH_Helpers::is_media() ) {
					wp_safe_redirect( esc_url( $redirect_page_url ) );
					exit();
				}
				// bbPress forums.
				if ( ( ( isset( $restrict_by_component['bbp'] ) && '1' === $restrict_by_component['bbp'] ) || beehive()->options->get( 'key=guest-bbp-redirection' ) ) && TH_Helpers::is_bbpress() ) {
					wp_safe_redirect( esc_url( $redirect_page_url ) );
					exit();
				}
				// Shop.
				if ( ( ( isset( $restrict_by_component['wc'] ) && '1' === $restrict_by_component['wc'] ) || beehive()->options->get( 'key=guest-wc-redirection' ) ) && TH_Helpers::is_woocommerce() ) {
					wp_safe_redirect( esc_url( $redirect_page_url ) );
					exit();
				}
				// Adverts.
				if ( ( ( isset( $restrict_by_component['wpad'] ) && '1' === $restrict_by_component['wpad'] ) || beehive()->options->get( 'key=guest-adverts-redirection' ) ) && TH_Helpers::is_advert() ) {
					wp_safe_redirect( esc_url( $redirect_page_url ) );
					exit();
				}
				// job manager.
				if ( ( ( isset( $restrict_by_component['wpjm'] ) && '1' === $restrict_by_component['wpjm'] ) || beehive()->options->get( 'key=guest-jobs-redirection' ) ) && TH_Helpers::is_job_manager() ) {
					wp_safe_redirect( esc_url( $redirect_page_url ) );
					exit();
				}
				// learnpress.
				if ( ( ( isset( $restrict_by_component['lp'] ) && '1' === $restrict_by_component['lp'] ) || beehive()->options->get( 'key=guest-lp-redirection' ) ) && TH_Helpers::is_learnpress() ) {
					wp_safe_redirect( esc_url( $redirect_page_url ) );
					exit();
				}
			} else {
				wp_safe_redirect( esc_url( $redirect_page_url ) );
				exit();
			}
		}
		return false;
	}
}
