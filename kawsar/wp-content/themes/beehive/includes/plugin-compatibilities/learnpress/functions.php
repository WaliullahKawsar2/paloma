<?php
/**
 * LearnPress functions
 *
 * Functions that will make learnpress compatible
 * with behive theme
 *
 * @package WordPress
 * @subpackage beehive
 * @since 1.2.0
 */

/** Do not allow directly accessing this file. */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Include learnpress
 * compatability class
 *
 * @since 1.2.0
 */
require_once BEEHIVE_INC . '/plugin-compatibilities/learnpress/class-beehive-learnpress.php';

/**
 * Remove actions
 *
 * @since 1.2.0
 */
remove_action( 'learn-press/before-main-content', 'learn_press_breadcrumb', 10 );
remove_action( 'learn-press/before-main-content', 'learn_press_search_form', 15 );

// Loop.
remove_action( 'learn-press/courses-loop-item-title', 'learn_press_courses_loop_item_thumbnail', 10 );
remove_action( 'learn-press/after-courses-loop-item', 'learn_press_courses_loop_item_price', 20 );
remove_action( 'learn-press/after-courses-loop-item', 'learn_press_courses_loop_item_instructor', 25 );

// Remove hooks from single course.
// Course meta.
// Course landing.
remove_action( 'learn-press/content-landing-summary', 'learn_press_course_meta_start_wrapper', 5 );
remove_action( 'learn-press/content-landing-summary', 'learn_press_course_students', 10 );
remove_action( 'learn-press/content-landing-summary', 'learn_press_course_meta_end_wrapper', 15 );

// Course learning.
remove_action( 'learn-press/content-learning-summary', 'learn_press_course_meta_start_wrapper', 10 );
remove_action( 'learn-press/content-learning-summary', 'learn_press_course_students', 15 );
remove_action( 'learn-press/content-learning-summary', 'learn_press_course_meta_end_wrapper', 20 );
remove_action( 'learn-press/content-learning-summary', 'learn_press_course_remaining_time', 30 );
remove_action( 'learn-press/content-learning-summary', 'learn_press_course_buttons', 40 );

// Remove hooks from course profile.
remove_action( 'learn-press/before-user-profile', 'learn_press_user_profile_header', 5 );

/**
 * Actions
 *
 * @since 1.2.0
 */
// Loop.
add_action( 'learn-press/before-main-content', 'beehive_lp_course_filter' );

add_action( 'learn-press/courses-loop-item-title', 'beehive_lp_loop_title_info', 5 );
add_action( 'learn-press/after-courses-loop-item', 'learn_press_courses_loop_item_instructor', 20 );
add_action( 'learn-press/after-courses-loop-item', 'learn_press_courses_loop_item_price', 25 );

// Add hooks in single course.
// Course landing.
add_action( 'beehive_single_course_meta', 'learn_press_course_instructor', 10 );
add_action( 'beehive_single_course_meta', 'learn_press_course_categories', 15 );
add_action( 'beehive_single_course_meta', 'beehive_lp_ratings_meta', 20 );
add_action( 'wp_head', 'beehive_lp_single_courses_actions' );
add_filter( 'learn-press/purchase-course-button-text', 'beehive_lp_purchase_button_text' );

// Course learning.
add_action( 'learn-press/before-single-course-curriculum', 'learn_press_course_remaining_time', 10 );

// Profiles.
add_action( 'learn-press/profile/after-dashboard', 'beehive_lp_dashboard_stats' );

// Remove learnpress inline css.
add_action(
	'learn-press/parse-course-item',
	function () {
		remove_action( 'wp_print_scripts', 'learn_press_content_item_script' );
	},
	10
);

/**
 * Filters
 *
 * @since 1.2.0
 */
add_filter( 'body_class', 'beehive_lp_body_classes' );
add_filter( 'nav_menu_css_class', 'beehive_lp_add_current_menu_class_on_archive', 10, 4 );
add_filter( 'learn_press_course_settings_meta_box_args', 'beehive_lp_add_course_meta' );
add_filter( 'learn-press/maximum-students-reach', 'beehive_lp_course_out_of_stock_msg' );
add_filter( 'learn-press/order-processing-message', 'beehive_lp_course_processing_order_msg' );

if ( ! function_exists( 'beehive_lp_course_filter' ) ) :
	/**
	 * Beehive course filter
	 *
	 * @since 1.2.0
	 */
	function beehive_lp_course_filter() {
		if ( learn_press_is_courses() ) {
			get_template_part( 'template-parts/learnpress/course-filters' );
		}
	}
endif;

if ( ! function_exists( 'beehive_lp_loop_title_info' ) ) :
	/**
	 * Beehive course loop info
	 *
	 * @since 1.2.5
	 */
	function beehive_lp_loop_title_info() {
		?>
		<div class="course-info-wrapper">
			<?php learn_press_courses_loop_item_students(); ?>
			<?php beehive_lp_course_rating_stars(); ?> 
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'beehive_lp_single_course_features' ) ) {
	/**
	 * Beehive single course features
	 *
	 * @since 1.2.0
	 */
	function beehive_lp_single_course_features() {
		get_template_part( 'template-parts/learnpress/single-course-features' );
	}
}

if ( ! function_exists( 'beehive_lp_add_course_meta' ) ) :
	/**
	 * Beehive single course features
	 *
	 * @param array $meta_box array of metabox.
	 * @return array
	 * @since 1.2.0
	 */
	function beehive_lp_add_course_meta( $meta_box ) {

		// Fields.
		$fields = $meta_box['fields'];

		// New fields.
		$fields[]           = array(
			'name' => esc_html__( 'Languages', 'beehive' ),
			'id'   => 'beehive_course_language',
			'type' => 'text',
			'desc' => esc_html__( 'Language\'s used in this course', 'beehive' ),
			'std'  => esc_html__( 'English', 'beehive' ),
		);
		$fields[]           = array(
			'name' => esc_html__( 'Course Level', 'beehive' ),
			'id'   => 'beehvie_course_level',
			'type' => 'text',
			'desc' => esc_html__( 'Course level i.e. Beginner/intermediate/advanced.', 'beehive' ),
			'std'  => esc_html__( 'Beginner', 'beehive' ),
		);
		$meta_box['fields'] = $fields;

		// return meta boxes.
		return $meta_box;
	}
endif;

if ( ! function_exists( 'beehive_related_courses' ) ) :
	/**
	 * Render related courses
	 *
	 * @return void
	 * @since 1.2.5
	 */
	function beehive_related_courses() {
		get_template_part( 'template-parts/learnpress/related', 'courses' );
	}
endif;

if ( ! function_exists( 'beehive_lp_course_out_of_stock_msg' ) ) :
	/**
	 * Out of stock message
	 *
	 * @return string
	 * @since 1.2.0
	 */
	function beehive_lp_course_out_of_stock_msg() {
		return esc_html__( 'Out of stock!', 'beehive' );
	}
endif;

if ( ! function_exists( 'beehive_lp_course_processing_order_msg' ) ) :
	/**
	 * Order processing message
	 *
	 * @return string
	 * @since 1.2.0
	 */
	function beehive_lp_course_processing_order_msg() {
		return esc_html__( 'Order processing!', 'beehive' );
	}
endif;

if ( ! function_exists( 'beehive_lp_body_classes' ) ) :

	/**
	 * Order processing message
	 *
	 * @param array $classes array of body classes.
	 * @return array
	 * @since 1.2.0
	 */
	function beehive_lp_body_classes( $classes ) {
		if ( learn_press_is_profile() ) {
			$classes[] = 'course-profile';
		}
		return $classes;
	}
endif;

if ( ! function_exists( 'beehive_lp_is_become_a_teacher' ) ) :
	/**
	 * Check if current page is become a teacher page.
	 *
	 * @return bool
	 * @since 1.2.0
	 */
	function beehive_lp_is_become_a_teacher() {
		$page_id = learn_press_get_page_id( 'become_a_teacher' );
		if ( ! empty( $page_id ) && is_page( $page_id ) ) {
			return true;
		}
		return false;
	}
endif;

if ( ! function_exists( 'beehive_lp_single_courses_actions' ) ) :
	/**
	 * Conditionally change single course actions.
	 *
	 * @return void
	 * @since 1.2.0
	 */
	function beehive_lp_single_courses_actions() {
		if ( learn_press_is_course() ) {
			if ( in_array( beehive()->layout->get(), array( 'social', 'social-12', 'social-collapsed', 'full' ), true ) ) {
				remove_action( 'learn-press/content-landing-summary', 'learn_press_course_price', 25 );
				remove_action( 'learn-press/content-landing-summary', 'learn_press_course_buttons', 30 );
				add_action( 'beehive_single_course_action', 'learn_press_course_price', 10 );
				add_action( 'beehive_single_course_action', 'learn_press_course_buttons', 15 );
			}
		}
	}
endif;

if ( ! function_exists( 'beehive_lp_purchase_button_text' ) ) :
	/**
	 * Purchase button text.
	 *
	 * @return string
	 * @since 1.2.5
	 */
	function beehive_lp_purchase_button_text() {
		return esc_html__( 'Purchase', 'beehive' );
	}
endif;

if ( ! function_exists( 'beehive_lp_dashboard_stats' ) ) :
	/**
	 * User dashboard stats.
	 *
	 * @return void
	 * @since 1.2.0
	 */
	function beehive_lp_dashboard_stats() {

		// User stats.
		$courses_completed  = 0;
		$courses_inprogress = 0;
		$quizzes_completed  = 0;
		$quizzes_inprogress = 0;

		// LP Profile.
		$profile = learn_press_get_profile();

		// User courses.
		$user_courses = $profile->query_courses( 'purchased' );
		if ( ! empty( $user_courses['items'] ) ) {
			foreach ( $user_courses['items'] as $user_course ) {
				$course_status = $user_course->get_results( 'status' );
				if ( 'passed' === $course_status ) {
					$courses_completed++;
				} elseif ( 'in-progress' === $course_status ) {
					$courses_inprogress++;
				}
			}
		}

		// User Quezes.
		$user_quizzes = $profile->query_quizzes();
		if ( ! empty( $user_quizzes ) ) {
			foreach ( $user_quizzes['items'] as $user_quiz ) {
				$quiz_status = $user_quiz->get_results( 'status' );
				if ( 'completed' === $quiz_status ) {
					$quizzes_completed++;
				} elseif ( 'started' === $quiz_status ) {
					$quizzes_inprogress++;
				}
			}
		}

		// Include the template.
		include locate_template( 'template-parts/learnpress/dashboard-stats.php' );
	}
endif;

if ( ! function_exists( 'beehive_lp_add_current_menu_class_on_archive' ) ) :
	/**
	 * Add current class to archive page.
	 *
	 * @param array  $classes array of classes.
	 * @param object $item menu item object.
	 * @param array  $args arguments.
	 * @return array
	 * @since 1.2.0
	 */
	function beehive_lp_add_current_menu_class_on_archive( $classes, $item, $args ) {
		if ( learn_press_is_courses() ) {
			if ( isset( $item->object_id ) && learn_press_get_page_id( 'courses' ) == $item->object_id ) {
				$classes[] = 'current';
			}
		}
		return $classes;
	}
endif;

if ( ! function_exists( 'beehive_lp_course_rating_stars' ) ) :
	/**
	 * Get course stars template.
	 *
	 * @return void
	 * @since 1.2.5
	 */
	function beehive_lp_course_rating_stars() {
		get_template_part( 'template-parts/learnpress/course-raitng' );
	}
endif;

if ( ! function_exists( 'beehive_lp_ratings_meta' ) ) :
	/**
	 * Single course rating meta.
	 *
	 * @return void
	 * @since 1.2.5
	 */
	function beehive_lp_ratings_meta() {
		if ( ! class_exists( 'LP_Addon_Course_Review' ) || ! learn_press_is_course() ) {
			return;
		}
		?>
		<div class="course-rating meta">
			<span class="label"><?php esc_html_e( 'Reviews', 'beehive' ); ?></span>
			<div class="rating">
				<?php beehive_lp_course_rating_stars(); ?>
			</div>
		</div>
		<?php
	}
endif;
