<?php
/**
 * Buddypress functions
 *
 * Functions that will make buddypress compatible
 * with beehive theme
 *
 * @package WordPress
 * @subpackage beehive
 * @since 1.0.0
 */

/** Do not allow directly accessing this file. */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Include buddypress
 * compatability class
 *
 * @since 1.0.0
 */
require_once BEEHIVE_INC . '/plugin-compatibilities/buddypress/class-beehive-buddypress.php';

/**
 * Actions
 *
 * @since 1.0.0
 */
add_action( 'bp_directory_members_actions', 'beehive_bp_member_loop_actions' );
add_action( 'bp_group_members_list_item_action', 'beehive_bp_member_loop_actions' );
add_action( 'bp_directory_groups_actions', 'beehive_bp_group_loop_actions' );
add_action( 'bp_member_header_actions', 'beehive_bp_logged_in_user_actions', 10, 1 );
add_action( 'bp_setup_nav', 'beehive_bp_change_profile_menu_positions', 999 );
add_action( 'bp_activity_entry_content', 'beehive_bp_mini_activity_entry_contents' );
add_action( 'bp_activity_before_save', 'beehive_bp_activity_do_not_save_mini_activities', 10, 1 );

/**
 * Filters
 *
 * @since 1.0.0
 */
add_filter( 'bp_before_members_cover_image_settings_parse_args', 'beehive_bp_xprofile_cover_image', 10, 1 );
add_filter( 'bp_before_groups_cover_image_settings_parse_args', 'beehive_bp_xprofile_cover_image', 10, 1 );
add_filter( 'rank_math/frontend/title', 'beehive_bp_rankmath_activation_page_title_fix' );

/**
 * Define avatar dimensions
 *
 * @since 1.0.0
 */
define( 'BP_AVATAR_FULL_WIDTH', 200 );
define( 'BP_AVATAR_FULL_HEIGHT', 200 );

if ( ! function_exists( 'beehive_bp_xprofile_cover_image' ) ) :
	/**
	 * Additional links for group loop
	 *
	 * @param array $settings settings array.
	 * @return array
	 * @since 1.0.0
	 */
	function beehive_bp_xprofile_cover_image( $settings = array() ) {
		$settings['width']  = 1920;
		$settings['height'] = 280;

		// Return settings.
		return $settings;
	}
endif;

if ( ! function_exists( 'beehive_bp_member_loop_actions' ) ) :
	/**
	 * Additional links for members loop
	 *
	 * @since 1.0.0
	 * @returns void
	 */
	function beehive_bp_member_loop_actions() {
		if ( ! bp_is_active( 'friends' ) ) {
			return;
		}
		if ( is_user_logged_in() ) {
			if ( bp_get_member_user_id() === bp_loggedin_user_id() ) {
				printf( '<li class="generic-button"><a href="%1$s">%2$s</a></li>', esc_url( bp_loggedin_user_domain() ), esc_html__( 'My Profile', 'beehive' ) );
			}
		} else {
			printf( '<li class="generic-button"><a href="%1$s">%2$s</a></li>', esc_url( bp_get_member_permalink() ), esc_html__( 'View Profile', 'beehive' ) );
		}
	}
endif;

if ( ! function_exists( 'beehive_bp_group_loop_actions' ) ) :
	/**
	 * Additional links for group loop
	 *
	 * @since 1.0.0
	 * @returns void
	 */
	function beehive_bp_group_loop_actions() {
		if ( is_user_logged_in() ) {
			$admin_count = count( BP_Groups_Member::get_group_administrator_ids( bp_get_group_id() ) );
			if ( ( groups_is_user_admin( bp_loggedin_user_id(), bp_get_group_id() ) ) && $admin_count < 2 && groups_is_user_member( bp_loggedin_user_id(), bp_get_group_id() ) ) {
				printf( '<li class="generic-button"><a href="%1$s">%2$s</a></li>', esc_url( trailingslashit( bp_get_group_permalink() . 'admin' ) ), esc_html__( 'Manage Group', 'beehive' ) );
			}
		} else {
			printf( '<li class="generic-button"><a href="%1$s">%2$s</a></li>', esc_url( bp_get_group_permalink() ), esc_html__( 'View Group', 'beehive' ) );
		}
	}
endif;

if ( ! function_exists( 'beehive_bp_logged_in_user_actions' ) ) :
	/**
	 * Logged in user profile actions
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function beehive_bp_logged_in_user_actions() {
		if ( bp_is_my_profile() ) {
			echo '<li class = "generic-button"><a class="edit-profile" href ="' . esc_url( bp_get_members_component_link( 'profile', 'edit' ) ) . '#item-body">' . esc_html__( 'Edit profile', 'beehive' ) . '</a><li>';
			if ( ! bp_disable_cover_image_uploads() ) {
				echo '<li class = "generic-button"><a class="update-cover" href="' . esc_url( bp_get_members_component_link( 'profile', 'change-cover-image' ) ) . '#item-body">' . esc_html__( 'Update cover', 'beehive' ) . '</a></li>';
			} else {
				echo '<li class = "generic-button"><a class="profile-settings" href="' . esc_url( bp_get_members_component_link( 'settings' ) ) . '#item-body">' . esc_html__( 'Profile settings', 'beehive' ) . '</a></li>';
			}
		}
	}

endif;

if ( ! function_exists( 'beehive_is_bp_profile_vartical_nav' ) ) {
	/**
	 * Check if profile nav is vertical
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	function beehive_is_bp_profile_vartical_nav() {

		$bp_nouveau        = bp_nouveau();
		$component         = bp_current_component();
		$customizer_option = ( bp_is_user() ) ? 'user_nav_display' : 'group_nav_display';
		$layout_prefs      = (int) bp_nouveau_get_temporary_setting( $customizer_option, bp_nouveau_get_appearance_settings( $customizer_option ) );

		if ( 1 === $layout_prefs ) {
			return true;
		} else {
			return false;
		}

		return false;
	}
}

if ( ! function_exists( 'beehive_bp_change_profile_menu_positions' ) ) :
	/**
	 * Change buddypress profile menu position
	 *
	 * @return void
	 * @since 1.0.0
	 */
	function beehive_bp_change_profile_menu_positions() {

		// BP Nav.
		$nav       = buddypress()->members->nav;
		$nav_items = array(
			'invitations'   => 285,
			'notifications' => 290,
			'messages'      => 295,
			'settings'      => 300,
		);

		// loop through the items and change position.
		foreach ( $nav_items as $nav_item => $position ) {
			$nav->edit_nav( array( 'position' => $position ), $nav_item );
		}

	}
endif;

if ( ! function_exists( 'beehive_bp_redirect_user_to_activity' ) ) {
	/**
	 * Redirect logged in user
	 * to activity page
	 *
	 * @return void
	 * @since 1.0.0
	 */
	function beehive_bp_redirect_user_to_activity() {
		if ( ! beehive()->options->get( 'key=home-to-activity' ) ) {
			return;
		}
		if ( ! is_user_logged_in() || current_user_can( 'manage_options' ) || is_home() || ( get_option( 'page_on_front' ) == bp_core_get_directory_page_id( 'activity' ) ) ) {
			return;
		}
		// Redirect user.
		if ( is_front_page() && ( false == ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( 'xmlhttprequest' == strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) ) ) ) {
			$page_id     = bp_core_get_directory_page_id( 'activity' );
			$redirect_to = get_the_permalink( $page_id );
			if ( $redirect_to ) {
				wp_safe_redirect( esc_url( $redirect_to ) );
				exit;
			}
		}
	}
}

if ( ! function_exists( 'beehive_bp_mini_activity_entry_contents' ) ) {
	/**
	 * Renders mini activity contents
	 *
	 * @return void
	 * @since 1.0.0
	 */
	function beehive_bp_mini_activity_entry_contents() {

		// Acitivity template global.
		global $activities_template;

		// Get Activity.
		$activity = $activities_template->activity;

		// Get templates.
		switch ( $activity->type ) {

			case 'new_member':
				include locate_template( 'template-parts/buddypress/user-activity-entries.php' );
				break;

			case 'friendship_created':
				include locate_template( 'template-parts/buddypress/user-activity-entries.php' );
				break;

			case 'updated_profile':
				include locate_template( 'template-parts/buddypress/user-activity-entries.php' );
				break;

			case 'new_avatar':
				include locate_template( 'template-parts/buddypress/user-activity-entries.php' );
				break;

			case 'created_group':
				include locate_template( 'template-parts/buddypress/group-activity-entries.php' );
				break;

			case 'joined_group':
				if ( bp_is_groups_component() ) {
					include locate_template( 'template-parts/buddypress/user-activity-entries.php' );
				} else {
					include locate_template( 'template-parts/buddypress/group-activity-entries.php' );
				}
				break;
		}

	}
}

if ( ! function_exists( 'beehive_bp_activity_do_not_save_mini_activities' ) ) :
	/**
	 * Don't save particular activity types.
	 * In the future.
	 *
	 * @param array $activity_object activity object.
	 * @return void
	 * @since 1.0.1
	 */
	function beehive_bp_activity_do_not_save_mini_activities( $activity_object ) {

		// What to exclude.
		$exclude = array();

		// Get options and push into exclude.
		$activity_types = beehive()->options->get( 'key=mini-activities' );
		if ( is_array( $activity_types ) && ! empty( $activity_types ) ) {
			foreach ( $activity_types as $activity_type => $value ) {
				if ( '1' === $value ) {
					array_push( $exclude, $activity_type );
				}
			}
		}

		// Exclude the items.
		if ( ! empty( $exclude ) ) {
			if ( in_array( $activity_object->type, $exclude, true ) ) {
				$activity_object->type = false;
			}
		}
	}
endif;

if ( ! function_exists( 'beehive_bp_get_bp_page_permalink' ) ) :
	/**
	 * Retrieve permalink for bp pages
	 *
	 * @param array $page page identifier.
	 * @return false/string
	 * @since 1.0.5
	 */
	function beehive_bp_get_bp_page_permalink( $page = 'activity' ) {
		$permalink = false;
		$bp_pages  = get_option( 'bp-pages' );
		if ( is_array( $bp_pages ) && ! empty( $bp_pages ) ) {
			if ( 'activity' === $page && isset( $bp_pages['activity'] ) ) {
				$permalink = get_permalink( $bp_pages['activity'] );
			} elseif ( 'members' === $page && isset( $bp_pages['members'] ) ) {
				$permalink = get_permalink( $bp_pages['members'] );
			} elseif ( 'groups' === $page && isset( $bp_pages['groups'] ) ) {
				$permalink = get_permalink( $bp_pages['groups'] );
			} elseif ( 'register' === $page && isset( $bp_pages['register'] ) ) {
				$permalink = get_permalink( $bp_pages['register'] );
			} elseif ( 'activate' === $page && isset( $bp_pages['activate'] ) ) {
				$permalink = get_permalink( $bp_pages['activate'] );
			}
		}
		return $permalink;
	}
endif;

if ( ! function_exists( 'beehive_bp_rankmath_activation_page_title_fix' ) ) :
	/**
	 * Fix bp activation page 404 title error
	 * while rankmath is active.
	 *
	 * @param string $title page title.
	 * @return string
	 * @since 1.3.5
	 */
	function beehive_bp_rankmath_activation_page_title_fix( $title ) {
		if ( bp_is_current_component( 'activate' ) ) {
			return '';
		}
		return $title;
	}
endif;

/**
 * Include like functions
 *
 * @since 1.0.0
 */
if ( beehive()->options->get( 'key=activity-like' ) ) {
	require_once BEEHIVE_INC . '/plugin-compatibilities/buddypress/like-functions.php';
}

/**
 * Buddypress xprofile field functions
 *
 * @since 1.0.0
 */
require_once BEEHIVE_INC . '/plugin-compatibilities/buddypress/xprofile-functions.php';
