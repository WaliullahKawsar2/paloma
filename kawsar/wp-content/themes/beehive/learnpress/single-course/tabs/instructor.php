<?php
/**
 * Template for displaying instructor of single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/instructor.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$course = LP_Global::course();
?>

<div class="course-author">
	<h3 class="screen-reader-text"><?php esc_html_e( 'Instructor', 'learnpress' ); ?></h3>
	<?php do_action( 'learn-press/before-single-course-instructor' ); ?>
	<div class="author-wrapper">
		<div class="author-avatar">
			<?php echo wp_kses_post( $course->get_instructor()->get_profile_picture() ); ?>
		</div>
		<div class="author-info">
			<h4 class="author-name"><?php echo wp_kses_post( $course->get_instructor_html() ); ?></h4>
			<div class="author-bio"><?php echo wp_kses_post( $course->get_author()->get_description() ); ?></div>
		</div>
	</div>
	<?php do_action( 'learn-press/after-single-course-instructor' ); ?>
</div>
