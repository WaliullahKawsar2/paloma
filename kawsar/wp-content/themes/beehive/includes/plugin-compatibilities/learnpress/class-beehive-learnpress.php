<?php
/**
 * Beehive LearnPress
 *
 * @author     thunder-team
 * @copyright  (c) Copyright by Thunder Team
 * @link       https://themeforest.net/user/thunder-team/
 * @package    WordPress
 * @subpackage beehive
 * @since      1.2.0
 */

/** Do not allow directly accessing this file. */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Beehive_Learnpress class.
 *
 * @since 1.2.0
 */
class Beehive_Learnpress {

	/**
	 * Shop sidebars
	 *
	 * @access public
	 * @var void
	 * @since 1.2.0
	 */
	public $sidebars;

	/**
	 * Instance
	 * The single instance of this class
	 *
	 * @access private
	 * @static
	 * @var object
	 * @since 1.2.0
	 */
	private static $_instance = null;

	/**
	 * Instance
	 * Ensures only one instance of the class is loaded or can be loaded
	 *
	 * @access public
	 * @static
	 * @return an instance of this class
	 * @since 1.2.0
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

		// Course sidebars.
		$this->sidebars = 'Courses Sidebar';
		// Course init.
		$this->init();

	}

	/**
	 * Init method
	 *
	 * @access public
	 * @return void
	 * @since 1.2.0
	 */
	public function init() {

		// Add shop sidebars.
		$this->add_sidbars();
		// Set sidebar.
		add_action( 'wp_head', array( $this, 'set_sidebar' ), 5 );
		// Set shop layout.
		add_action( 'wp_head', array( $this, 'layout' ), 5 );
		// After theme setup.
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
		// Insert shop Menu.
		add_action( 'beehive_before_main_content', array( $this, 'get_course_menu' ) );
		// Remove stylesheets.
		add_filter( 'learn-press/frontend-default-styles', array( $this, 'remove_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'remove_addon_styles' ), 11 );
		// Add stylesheets.
		add_filter( 'beehive_stylesheets', array( $this, 'add_styles' ), 10, 2 );
		// Add scripts.
		add_filter( 'beehive_scripts', array( $this, 'add_scripts' ), 10, 2 );

	}

	/**
	 * Add Sidebars
	 *
	 * @access public
	 * @return void
	 * @since 1.2.0
	 */
	public function add_sidbars() {
		beehive()->sidebars->add( $this->sidebars );
	}

	/**
	 * Set generic sidebar course pages.
	 *
	 * @access public
	 * @return void
	 * @since 1.2.0
	 */
	public function set_sidebar() {
		if ( TH_Helpers::is_learnpress() ) {
			beehive()->sidebars->set( $this->sidebars );
		}
	}

	/**
	 * Set layout style
	 *
	 * @access public
	 * @return void
	 * @since 1.2.0
	 */
	public function layout() {

		// Return early.
		if ( ! TH_Helpers::is_learnpress() ) {
			return;
		}

		// Set the layout.
		if ( learn_press_is_course() ) {
			beehive()->layout->set( 'social-12' );
		} elseif ( learn_press_is_profile() || learn_press_is_checkout() ) {
			beehive()->layout->set( 'social-collapsed' );
		} else {
			beehive()->layout->set( 'social' );
		}
	}

	/**
	 * After theme setup
	 *
	 * @access public
	 * @return void
	 * @since 1.2.0
	 */
	public function after_setup_theme() {

		// Register shop menu.
		register_nav_menus(
			array(
				'course_menu' => esc_html__( 'Course Menu', 'beehive' ),
			)
		);

	}

	/**
	 * Get the course menu
	 *
	 * @access  public
	 * @return void
	 * @since 1.2.0
	 */
	public function get_course_menu() {
		if ( learn_press_is_courses() || beehive_lp_is_become_a_teacher() ) {
			get_template_part( 'template-parts/learnpress/course-menu' );
		}
	}

	/**
	 * Remove styles from plugin dir
	 *
	 * @access public
	 * @param array $enqueue_styles learnpress stylesheets.
	 * @return array
	 * @since 1.2.0
	 */
	public function remove_styles( $enqueue_styles ) {
		unset( $enqueue_styles['learn-press'] );
		return $enqueue_styles;
	}

	/**
	 * Remove LP addon styles
	 *
	 * @access public
	 * @return void
	 * @since 1.0.0
	 */
	public function remove_addon_styles() {
		if ( class_exists( 'LP_Addon_BuddyPress' ) ) {
			wp_dequeue_style( 'learn-press-buddypress' );
		}
		if ( class_exists( 'LP_Addon_Course_Review' ) ) {
			wp_dequeue_style( 'course-review' );
		}
		if ( class_exists( 'LP_Addon_Fill_In_Blank' ) ) {
			wp_dequeue_style( 'lp-fib-question-css' );
		}
	}

	/**
	 * Add Styles
	 *
	 * @access public
	 * @param array  $styles beehive learnpress stylesheets.
	 * @param string $min    minified version of css.
	 * @return array
	 * @since 1.0.0
	 */
	public function add_styles( $styles, $min ) {

		// Learnpress main stylesheet.
		$styles['beehive-courses'] = array(
			'src'     => BEEHIVE_URI . '/learnpress/assets/css/learnpress' . $min . '.css',
			'deps'    => array(),
			'version' => beehive()->version(),
			'media'   => 'all',
			'enqueue' => false,
		);

		// Course review addon styles.
		if ( class_exists( 'LP_Addon_Course_Review' ) ) {
			$styles['beehive-lp-course-review'] = array(
				'src'     => BEEHIVE_URI . '/learnpress/assets/css/lp-course-review' . $min . '.css',
				'deps'    => array(),
				'version' => beehive()->version(),
				'media'   => 'all',
				'enqueue' => false,
			);
		}

		// Fill in the blank addon styles.
		if ( class_exists( 'LP_Addon_Fill_In_Blank' ) ) {
			$styles['beehive-lp-fib'] = array(
				'src'     => BEEHIVE_URI . '/learnpress/assets/css/lp-fib' . $min . '.css',
				'deps'    => array(),
				'version' => beehive()->version(),
				'media'   => 'all',
				'enqueue' => false,
			);
		}

		// Enqueue style.
		if ( TH_Helpers::is_learnpress() ) {
			$styles['beehive-courses']['enqueue'] = true;
		}

		// Enqueue addon styles.
		if ( isset( $styles['beehive-lp-course-review'] ) && TH_Helpers::is_learnpress() ) {
			$styles['beehive-lp-course-review']['enqueue'] = true;
		}
		if ( isset( $styles['beehive-lp-fib'] ) && TH_Helpers::is_learnpress() ) {
			$styles['beehive-lp-fib']['enqueue'] = true;
		}

		// Enqueue lp bp styles.
		if ( class_exists( 'LP_Addon_BuddyPress' ) && function_exists( 'bp_is_active' ) ) {
			$bp_current_component = bp_current_component();
			switch ( $bp_current_component ) {
				case 'courses':
					$styles['beehive-courses']['enqueue'] = true;
					if ( isset( $styles['beehive-lp-course-review'] ) ) {
						$styles['beehive-lp-course-review']['enqueue'] = true;
					}
					break;
				case 'quizzes':
					$styles['beehive-courses']['enqueue'] = true;
					break;
				case 'orders':
					$styles['beehive-courses']['enqueue'] = true;
					break;
			}
		}

		// Return stylesheets.
		return $styles;
	}

	/**
	 * Add scripts
	 *
	 * @access public
	 * @param array  $scripts scripts.
	 * @param string $min     minified version of the scripts.
	 * @return array
	 * @since 1.0.0
	 */
	public function add_scripts( $scripts, $min ) {

		// Activity like.
		$scripts['beehive-lp'] = array(
			'src'       => BEEHIVE_URI . '/learnpress/assets/js/beehive-lp' . $min . '.js',
			'deps'      => array( 'jquery' ),
			'in_footer' => true,
			'enqueue'   => false,
		);

		// Enqueue script.
		if ( TH_Helpers::is_learnpress() ) {
			$scripts['beehive-lp']['enqueue'] = true;
		}
		if ( class_exists( 'LP_Addon_BuddyPress' ) && function_exists( 'bp_is_active' ) ) {
			$bp_current_component = bp_current_component();
			switch ( $bp_current_component ) {
				case 'courses':
					$scripts['beehive-lp']['enqueue'] = true;
					break;
				case 'quizzes':
					$scripts['beehive-lp']['enqueue'] = true;
					break;
				case 'orders':
					$scripts['beehive-lp']['enqueue'] = true;
					break;
			}
		}

		// Return scripts.
		return $scripts;

	}

}

// Single instance of this class.
Beehive_Learnpress::instance();
