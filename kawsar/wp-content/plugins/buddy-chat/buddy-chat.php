<?php

/**
 * The plugin bootstrap file
 *
 * @link              none
 * @since             1.0.0
 * @package           Buddy_Chat
 *
 * @wordpress-plugin
 * Plugin Name:       BuddyChat
 * Plugin URI:        https://themified.com/beehive/
 * Description:       A simple user and group chat plugin for buddypress
 * Version:           1.1.2
 * Author:            zeroone
 * Author URI:        https://themified.com/beehive/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       buddy-chat
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BUDDY_CHAT_VERSION', '1.1.2' );
define( 'BUDDY_CHAT_BASE_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-buddy-chat-activator.php
 */
function activate_buddy_chat() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-buddy-chat-activator.php';
	Buddy_Chat_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-buddy-chat-deactivator.php
 */
function deactivate_buddy_chat() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-buddy-chat-deactivator.php';
	Buddy_Chat_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_buddy_chat' );
register_deactivation_hook( __FILE__, 'deactivate_buddy_chat' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-buddy-chat.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_buddy_chat() {

	$plugin = new Buddy_Chat();
	$plugin->run();

}
add_action( 'bp_include', 'run_buddy_chat' );
