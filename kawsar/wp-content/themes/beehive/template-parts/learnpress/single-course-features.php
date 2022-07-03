<?php
/**
 * Course
 *
 * Displays course features overview on
 * single course page.
 *
 * @package WordPress
 * @subpackage beehive
 * @since 1.2.0
 */

/** Do not allow directly accessing this file. */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' ); } ?>

<?php
if ( ! learn_press_is_course() ) {
	return; }
?>

<?php
$course = LP()->global['course'];
if ( ! empty( $course->get_data( 'duration' ) ) ) {
	$duration       = $course->get_data( 'duration' );
	$duration_array = explode( ' ', $duration );
	if ( count( $duration_array ) === 2 ) {
		$duration_number = intval( $duration_array[0] );
		$duration_time   = $duration_array[1];
		switch ( $duration_time ) {
			case 'week':
				// translators: course duration in weeks.
				$duration = sprintf( _n( '%s week', '%s weeks', $duration_number, 'beehive' ), number_format_i18n( $duration_number ) );
				break;
			case 'day':
				// translators: course duration in days.
				$duration = sprintf( _n( '%s day', '%s days', $duration_number, 'beehive' ), number_format_i18n( $duration_number ) );
				break;
			case 'hour':
				// translators: course duration in hours.
				$duration = sprintf( _n( '%s hour', '%s hours', $duration_number, 'beehive' ), number_format_i18n( $duration_number ) );
				break;
			case 'minute':
				// translators: course duration in minutes.
				$duration = sprintf( _n( '%s minute', '%s minutes', $duration_number, 'beehive' ), number_format_i18n( $duration_number ) );
				break;
		}
	}
}
?>

<div class="single-course-features">
	<div class="features-title">
		<h5 class="title"><?php esc_html_e( 'Course Features', 'beehive' ); ?></h5>
	</div>
	<ul class="course-feature-lists">
		<li class="item lectures">
			<?php $lecture_count = $course->get_curriculum_items( 'lp_lesson' ) ? count( $course->get_curriculum_items( 'lp_lesson' ) ) : 0; ?>
			<span class="label"><i class="uil-file-alt"></i><?php esc_html_e( 'Lectures', 'beehive' ); ?></span>
			<span class="value"><?php echo esc_html( $lecture_count ); ?></span>
		</li>
		<?php if ( isset( $duration ) && ! empty( $duration ) ) : ?>
			<li class="item duration">
				<span class="label"><i class="uil-clock-eight"></i><?php esc_html_e( 'Duration', 'beehive' ); ?></span>
				<span class="value"><?php echo esc_html( $duration ); ?></span>
			</li>
		<?php endif; ?>
		<?php if ( get_post_meta( get_the_ID(), 'beehvie_course_level', true ) ) : ?>
			<li class="item level">
				<span class="label"><i class="uil-graph-bar"></i><?php esc_html_e( 'Level', 'beehive' ); ?></span>
				<span class="value"><?php echo esc_html( get_post_meta( get_the_ID(), 'beehvie_course_level', true ) ); ?></span>
			</li>
		<?php endif; ?>
		<?php if ( get_post_meta( get_the_ID(), 'beehive_course_language', true ) ) : ?>
			<li class="item language">
				<span class="label"><i class="uil-text-size"></i><?php esc_html_e( 'Language', 'beehive' ); ?></span>
				<span class="value"><?php echo esc_html( get_post_meta( get_the_ID(), 'beehive_course_language', true ) ); ?></span>
			</li>
		<?php endif; ?>
		<li class="item quizzes">
			<?php $quiz_count = $course->get_curriculum_items( 'lp_quiz' ) ? count( $course->get_curriculum_items( 'lp_quiz' ) ) : 0; ?>
			<span class="label"><i class="uil-puzzle-piece"></i><?php esc_html_e( 'Quizzes', 'beehive' ); ?></span>
			<span class="value"><?php echo esc_html( $quiz_count ); ?></span>
		</li>
		<li class="item students">
			<?php $user_count = $course->get_users_enrolled() ? $course->get_users_enrolled() : 0; ?>
			<span class="label"><i class="uil-user-check"></i><?php esc_html_e( 'Students', 'beehive' ); ?></span>
			<span class="value"><?php echo esc_html( $user_count ); ?></span>
		</li>
	</ul>
</div>
