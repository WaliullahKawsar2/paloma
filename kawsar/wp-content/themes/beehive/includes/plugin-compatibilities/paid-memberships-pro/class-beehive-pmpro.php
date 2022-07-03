<?php
/**
 * Beehive paid membership pro.
 *
 * @author     thunder-team
 * @copyright  (c) Copyright by Thunder Team
 * @link       https://themeforest.net/user/thunder-team/
 * @package    WordPress
 * @subpackage beehive
 * @since      1.3.5
 */

/** Do not allow directly accessing this file. */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' ); }

/**
 * Beehive_Job_Manager class.
 *
 * @since 1.3.5
 */
class Beehive_PM_Pro {

	/**
	 * Instance
	 * The single instance of this class
	 *
	 * @access private
	 * @static
	 * @var object
	 * @since 1.3.5
	 */
	private static $_instance = null;

	/**
	 * Instance
	 * Ensures only one instance of the class is loaded or can be loaded
	 *
	 * @access public
	 * @static
	 * @return an instance of this class
	 * @since 1.3.5
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * The class constructor
	 *
	 * @access public
	 */
	public function __construct() {

		// PMpro init.
		$this->init();

	}

	/**
	 * Init method
	 *
	 * @access public
	 * @return void
	 * @since 1.3.5
	 */
	public function init() {

		// Set layout.
		add_action( 'wp_head', array( $this, 'layout' ), 5 );

	}

	/**
	 * Set layout style
	 *
	 * @access public
	 * @return string
	 * @since 1.3.5
	 */
	public function layout() {

		// Return early if not a pm pro page.
		if ( ! TH_Helpers::is_pmpro() ) {
			return;
		}

		// Set the layout style.
		beehive()->layout->set( 'social-collapsed' );
	}

}

// Single instance of this class.
Beehive_PM_Pro::instance();
