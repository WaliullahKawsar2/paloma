<?php
/**
 * Template for displaying course students within the loop.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/loop/course/students.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$course = LP_Global::course();
?>

<div class="course-students">

	<span>
		<?php echo esc_html( $course->get_users_enrolled() ); ?>
		<?php if ( $course->get_users_enrolled() > 1 ) : ?>
			<?php esc_html_e( 'students', 'learnpress' ); ?>
		<?php else : ?>
			<?php esc_html_e( 'student', 'learnpress' ); ?>
		<?php endif; ?>
	</span>

</div>
