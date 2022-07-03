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
 * Beehive_Job_Categories_Shortcode class.
 *
 * @extends WP_Widget
 * @since 1.0.0
 */
class Beehive_Job_Categories_Shortcode {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name       The name of the plugin.
	 * @param string $version           The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Add plugin shortcodes
	 *
	 * @access    public
	 * @since     1.0.0
	 * @return    void
	 */
	public function add_shortcodes() {
		add_shortcode( 'beehive_wpjm_categories', array( $this, 'beehive_wpjm_categories' ) );
	}

	/**
	 * Get plugin template part
	 *
	 * @param     array $file file name.
	 * @param     mixed $variables varibales to pass.
	 * @access    public
	 * @since     1.0.0
	 * @return    void
	 */
	protected function get_template_part( $file, $variables = null ) {
		$located = locate_template( 'theme-overrides/' . $this->plugin_name . '/' . $file . '.php' );
		if ( is_array( $variables ) ) {
			extract( $variables, EXTR_SKIP ); // @codingStandardsIgnoreLine
		}
		if ( empty( $located ) ) {
			$path = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/' . $file . '.php';
			if ( file_exists( $path ) ) {
				include $path;
			}
		} else {
			include $located;
		}
	}

	/**
	 * Load job categories shortcode.
	 *
	 * @param     array $atts shortcode attributes.
	 * @access    public
	 * @since     1.0.0
	 * @return    string
	 */
	public function beehive_wpjm_categories( $atts ) {

		// Shortcode atts.
		$params = shortcode_atts(
			array(
				'show'        => 'top',
				'columns'     => 3,
				'show_count'  => true,
				'child_count' => 5,
			),
			$atts
		);

		// taxonomy.
		$taxonomy = 'job_listing_category';
		if ( ! taxonomy_exists( $taxonomy ) ) {
			if ( current_user_can( 'manage_options' ) ) {
				return '<p>' . esc_html__( 'Enable job categories in the wp job manager settings.', 'beehive-job-categories' ) . '</p>';
			} else {
				return '<p>' . esc_html__( 'Nothing found!', 'beehive-job-categories' ) . '</p>';
			}
		} else {
			$listing_classes = array( 'job-category-listings' );
			$show            = $params['show'];
			if ( 'top' !== $params['show'] ) {
				$show = 'all';
				array_push( $listing_classes, 'all-listings' );
			} else {
				array_push( $listing_classes, 'top-listings' );
			}
			if ( ! empty( $params['columns'] ) && is_numeric( $params['columns'] ) ) {
				array_push( $listing_classes, 'beehive-columns columns-' . (int) abs( round( $params['columns'] ) ) );
			} else {
				array_push( $listing_classes, 'beehive-columns columns-3' );
			}
			$terms = get_terms(
				array(
					'taxonomy'   => $taxonomy,
					'parent'     => null,
					'hide_empty' => 0,
				)
			);

			// Get template and return shortcode.
			$variables = compact( 'params', 'listing_classes', 'show', 'terms' );
			ob_start();
			$this->get_template_part( 'content-job-categories', $variables );
			return ob_get_clean();
		}
	}
}
