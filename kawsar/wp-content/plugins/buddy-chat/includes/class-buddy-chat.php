<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       none
 * @since      1.0.0
 *
 * @package    Buddy_Chat
 * @subpackage Buddy_Chat/includes
 */

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
 * @package    Buddy_Chat
 * @subpackage Buddy_Chat/includes
 * @author     Nono <none>
 */
class Buddy_Chat {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Buddy_Chat_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

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
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'BUDDY_CHAT_VERSION' ) ) {
			$this->version = BUDDY_CHAT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'buddy-chat';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Buddy_Chat_Loader. Orchestrates the hooks of the plugin.
	 * - Buddy_Chat_i18n. Defines internationalization functionality.
	 * - Buddy_Chat_Admin. Defines all hooks for the admin area.
	 * - Buddy_Chat_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-buddy-chat-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-buddy-chat-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-buddy-chat-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-buddy-chat-public.php';
    
    if ( !class_exists( 'ReduxFramework' ) && file_exists( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/ReduxFramework/ReduxCore/framework.php' ) ) {
      require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/ReduxFramework/ReduxCore/framework.php' );
    }
    require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/redux-options.php' );

		$this->loader = new Buddy_Chat_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Buddy_Chat_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Buddy_Chat_i18n();

		$this->loader->add_action( 'init', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Buddy_Chat_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

    if(is_user_logged_in()) {
      $plugin_public = new Buddy_Chat_Public( $this->get_plugin_name(), $this->get_version() );

      $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
      $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

      $this->loader->add_action( 'admin_post_bpc_event_source', $plugin_public, 'bpc_event_source' );
      $this->loader->add_action( 'wp_ajax_bpc_chat_buddies', $plugin_public, 'bpc_chat_buddies' );
      $this->loader->add_action( 'wp_ajax_bpc_all_users', $plugin_public, 'bpc_all_users' );
      $this->loader->add_action( 'wp_ajax_bpc_all_friends', $plugin_public, 'bpc_all_friends' );
      $this->loader->add_action( 'wp_ajax_bpc_online_users', $plugin_public, 'bpc_online_users' );
      $this->loader->add_action( 'wp_ajax_bpc_send_message', $plugin_public, 'bpc_send_message' );
      $this->loader->add_action( 'wp_ajax_bpc_get_message', $plugin_public, 'bpc_get_message' );

      $this->loader->add_action( 'wp_footer', $plugin_public, 'bpc_template' );
    }

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
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
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Buddy_Chat_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
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
