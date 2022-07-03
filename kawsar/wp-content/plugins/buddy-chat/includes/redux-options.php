<?php

/**
 * ReduxFramework Barebones Sample Config File
 * For full documentation, please visit: http://docs.reduxframework.com/
 */
if ( ! class_exists( 'Redux' ) ) {
	return;
}

// This is your option name where all the Redux data is stored.
$opt_name = 'buddychat_options';

$args = array(
	// TYPICAL -> Change these values as you need/desire.
	'opt_name'             => $opt_name,
	// This is where your data is stored in the database and also becomes your global variable name.
	'display_name'         => 'BuddyChat Options',
	// Name that appears at the top of your panel.
	'display_version'      => BUDDY_CHAT_VERSION,
	// Version that appears at the top of your panel.
	'menu_type'            => 'menu',
	// Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only).
	'allow_sub_menu'       => false,
	// Show the sections below the admin menu item or not.
	'menu_title'           => esc_html__( 'BuddyChat', 'buddy-chat' ),
	'page_title'           => esc_html__( 'BuddyChat Options', 'buddy-chat' ),
	// Set it you want google fonts to update weekly. A google_api_key value is required.
	'google_update_weekly' => false,
	// Must be defined to add google fonts to the typography module.
	'async_typography'     => false,
	// Use a asynchronous font on the front end or font string.
	// 'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader.
	'admin_bar'            => false,
	// Show the panel pages on the admin bar.
	'admin_bar_icon'       => 'dashicons-portfolio',
	// Choose an icon for the admin bar menu.
	'admin_bar_priority'   => 50,
	// Choose an priority for the admin bar menu.
	'global_variable'      => '',
	// Set a different name for your global variable other than the opt_name.
	'dev_mode'             => false,
	// Show the time the page took to load, etc.
	'update_notice'        => false,
	// If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo.
	'customizer'           => false,
	// Enable basic customizer support
	// 'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
	// 'disable_save_warn' => true,                    // Disable the save warning when a user changes a field
	// OPTIONAL -> Give you extra features.
	'page_priority'        => null,
	// Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
	'page_parent'          => 'themes.php',
	// For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters.
	'page_permissions'     => 'manage_options',
	// Permissions needed to access the options panel.
	'menu_icon'            => 'dashicons-format-chat',
	// Specify a custom URL to an icon.
	'last_tab'             => '',
	// Force your panel to always open to a specific tab (by id).
	'page_icon'            => 'icon-themes',
	// Icon displayed in the admin panel next to your menu_title.
	'page_slug'            => 'buddy_chat_options',
	// Page slug used to denote the panel.
	'save_defaults'        => true,
	// On load save the defaults to DB before user clicks save or not.
	'default_show'         => false,
	// If true, shows the default value next to each field that is not the default value.
	'default_mark'         => '',
	// What to print by the field's title if the value shown is default. Suggested: *.
	'show_import_export'   => false,
	// Shows the Import/Export panel when not used as a field.
	// CAREFUL -> These options are for advanced use only.
	'transient_time'       => 60 * MINUTE_IN_SECONDS,
	'output'               => true,
	// Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output.
	'output_tag'           => true,
	// Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
	// 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.
	// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
	'database'             => '',
	// possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
	'use_cdn'              => true,
	// If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.
	// 'compiler'             => true,
	// HINTS.
	'hints'                => array(
		'icon'          => 'el el-question-sign',
		'icon_position' => 'right',
		'icon_color'    => 'lightgray',
		'icon_size'     => 'normal',
		'tip_style'     => array(
			'color'   => 'light',
			'shadow'  => true,
			'rounded' => false,
			'style'   => '',
		),
		'tip_position'  => array(
			'my' => 'top left',
			'at' => 'bottom right',
		),
		'tip_effect'    => array(
			'show' => array(
				'effect'   => 'slide',
				'duration' => '500',
				'event'    => 'mouseover',
			),
			'hide' => array(
				'effect'   => 'slide',
				'duration' => '500',
				'event'    => 'click mouseleave',
			),
		),
	),
);

Redux::setArgs( $opt_name, $args );

// -> START Basic Fields.
Redux::setSection(
	$opt_name,
	array(
		'title'  => __( 'Settings', 'buddy-chat' ),
		'id'     => 'buddychat-setting',
		'icon'   => 'el el-home',
		'fields' => array(
			array(
				'id'      => 'title',
				'type'    => 'text',
				'title'   => esc_html__( 'Messenger box title', 'buddy-chat' ),
			),
			array(
				'id'      => 'perpage',
				'type'    => 'spinner',
				'title'   => esc_html__( 'Buddy pagination', 'buddy-chat' ),
				'default' => '30',
				'min'     => '10',
				'step'    => '5',
				'max'      => '150',
				
			),
			array(
				'id'             => 'notification',
				'type'           => 'media',
				'title'          => esc_html__( 'Notification tone', 'buddy-chat' ),
				'url'            => true,
				'preview'        => false,
				'default'        => array(
					'url' => BUDDY_CHAT_BASE_URL . 'public/sounds/notification.mp3',
				),
				'mode'           => 'audio',
				'library_filter' => array(
					'mp3',
					'ogg',
				),
			),
			array(
				'id'      => 'available-tabs',
				'type'    => 'checkbox',
				'title'   => esc_html__( 'Available tabs', 'buddy-chat' ),
				'default' => array(
					'all-members' => '0',
					'friends'     => '1',
					'groups'      => '1',
				),
				'options' => array(
					'all-members' => esc_html__('All Members', 'buddy-chat'),
					'friends'     => esc_html__('Friends', 'buddy-chat'),
					'groups'      => esc_html__('Groups', 'buddy-chat'),
				),
			),
			array(
				'id'      => 'active-tab',
				'type'    => 'select',
				'title'   => esc_html__( 'Active tab', 'buddy-chat' ),
				'default' => 'friends',
				'options' => array(
					'all-members' => esc_html__('All Members', 'buddy-chat'),
					'friends'     => esc_html__('Friends', 'buddy-chat'),
					'groups'      => esc_html__('Groups', 'buddy-chat'),
				),
			)
		),
	)
);
