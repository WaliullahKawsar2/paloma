<?php
/**
 * Template for displaying instructor of course within the loop.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/loop/course/instructor.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.1
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$course = LP_Global::course();

if ( ! $course ) {
	return;
}
?>

<div class="course-instructor">
	<span class="author-avatar"><?php echo get_avatar( get_the_author_meta( 'ID' ), 40 ); ?></span>
	<?php echo wp_kses_post( $course->get_instructor_html() ); ?>
</div>
