<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://themeforest.net/user/thunder-team
 * @since      1.0.0
 *
 * @package    Beehive_Job_Categories
 * @subpackage Beehive_Job_Categories/includes
 */

defined( 'ABSPATH' ) || exit;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Beehive_Job_Categories
 * @subpackage Beehive_Job_Categories/includes
 * @author     thunder-team <drinkingherredtear@gmail.com>
 */
class Beehive_Job_Categories {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Instance
	 * The single instance of this class
	 *
	 * @access private
	 * @static
	 * @var object
	 * @since 1.0.0
	 */
	private static $_instance = null;

	/**
	 * Instance
	 * Ensures only one instance of the class is loaded or can be loaded
	 *
	 * @access public
	 * @static
	 * @return an instance of this class
	 * @since 1.0.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'BEEHIVE_JOB_CATEGORIES_VERSION' ) ) {
			$this->version = BEEHIVE_JOB_CATEGORIES_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'beehive-job-categories';
		if ( true === $this->is_wpjm_installed() ) {
			$this->load_files();
			$this->shortcodes();
		}
		add_action( 'init', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Check if wp job manager plugin is installed.
	 * This plugin is an addon of job manager plugin so checks must be done.
	 *
	 * @since     1.0.0
	 * @return    bool
	 */
	public function is_wpjm_installed() {
		return (bool) in_array( 'wp-job-manager/wp-job-manager.php', get_option( 'active_plugins', array() ), true );
	}

	/**
	 * Load plugin files.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	private function load_files() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpjm-categories-shortcode.php';
	}

	/**
	 * Initiate shortcode class.
	 * And also add shortcodes.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	private function shortcodes() {
		$shortcodes = new Beehive_Job_Categories_Shortcode( $this->get_plugin_name(), $this->get_version() );
		$shortcodes->add_shortcodes();
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_textdomain() {

		load_plugin_textdomain(
			'beehive-job-categories',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
