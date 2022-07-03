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
 * @package    Beehive_Widgets
 * @subpackage Beehive_Widgets/includes
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
 * @package    Beehive_Widgets
 * @subpackage Beehive_Widgets/includes
 * @author     thunder-team <drinkingherredtear@gmail.com>
 */
class Beehive_Widgets {

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

		// set version.
		if ( defined( 'BEEHIVE_WIDGETS_VERSION' ) ) {
			$this->version = BEEHIVE_WIDGETS_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		// Set plugin name.
		$this->plugin_name = 'beehive-widgets';

		// Plugin text domain.
		add_action( 'init', array( $this, 'load_textdomain' ) );

		// Load widgets.
		$this->load_widgets();
	}

	/**
	 * Load all the widget files
	 *
	 * @since    1.0.0
	 * @access   private
	 * @return   array
	 */
	private function get_widgets() {

		// Widgets.
		$widgets = array();
		// Active plugins.
		$plugins = get_option( 'active_plugins', array() );

		// beehive bp latest activity widget.
		if ( in_array( 'buddypress/bp-loader.php', $plugins, true ) ) {
			array_push( $widgets, 'beehive-widget-latest-activity.php' );
		}

		// Return widgets.
		return $widgets;
	}

	/**
	 * Load all the widget files
	 *
	 * @since    1.0.0
	 * @access   private
	 * @return   void
	 */
	public function load_widgets() {

		// Get Widgets.
		$widgets = $this->get_widgets();

		if ( ! empty( $widgets ) ) {
			foreach ( $widgets as $widget ) {
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widgets/' . $widget;
			}
		}
	}

	/**
	 * Load the plugin text domain for translation.
	 * so that it is ready for translation.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'beehive-widgets',
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

