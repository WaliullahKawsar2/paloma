<?php
/**
 * Template for displaying quiz result.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-quiz/result.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$user      = LP_Global::user();
$quiz      = LP_Global::course_item_quiz();
$quiz_data = $user->get_quiz_data( $quiz->get_id() );
$result    = $quiz_data->get_results( false );

if ( $quiz_data->is_review_questions() ) {
	return;
} ?>

<div class="quiz-result <?php echo esc_attr( $result['grade'] ); ?>">
	<h3><?php esc_html_e( 'Your Result', 'learnpress' ); ?></h3>
	<?php
	if ( $result['grade'] ) {
		if ( 'point' === $quiz->get_passing_grade() ) {
			$pass_point = $quiz->get_data( 'passing_grade' );
		} else {
			$pass_point = round( $quiz->get_data( 'passing_grade' ) ) . '%';
		}
		$percent_result = $quiz_data->get_percent_result();
		if ( 'passed' === $result['grade'] ) {
			$class = 'success';
			$grade = esc_html__( 'passed', 'beehive' );
		} else {
			$class = 'error';
			$grade = esc_html__( 'failed', 'beehive' );
		}
		// translators: result output text.
		learn_press_display_message( sprintf( __( 'Your quiz grade <b>%1$s</b>. The result is %2$s (the requirement is %3$s).', 'beehive' ), $grade, $percent_result, $pass_point ), $class );
	}
	?>
	<ul class="result-statistic">
		<li class="result-statistic-field">
			<div class="result-field-wrapper">
				<label><?php echo esc_html_x( 'Time spend', 'quiz-result', 'learnpress' ); ?></label>
				<p><?php echo esc_html( $result['time_spend'] ); ?></p>
			</div>
		</li>
		<li class="result-statistic-field">
			<div class="result-field-wrapper">
				<label><?php echo esc_html_x( 'Point', 'quiz-result', 'learnpress' ); ?></label>
				<p><?php echo esc_html( $result['user_mark'] . ' / ' . $result['mark'] ); ?></p>
			</div>
		</li>
		<li class="result-statistic-field">
			<div class="result-field-wrapper">
				<label><?php echo esc_html_x( 'Questions', 'quiz-result', 'learnpress' ); ?></label>
				<p><?php echo esc_html( $quiz->count_questions() ); ?></p>
			</div>
		</li>
		<li class="result-statistic-field">
			<div class="result-field-wrapper">
				<label><?php echo esc_html_x( 'Correct', 'quiz-result', 'learnpress' ); ?></label>
				<p><?php echo esc_html( $result['question_correct'] ); ?></p>
			</div>
		</li>
		<li class="result-statistic-field">
			<div class="result-field-wrapper">
				<label><?php echo esc_html_x( 'Wrong', 'quiz-result', 'learnpress' ); ?></label>
				<p><?php echo esc_html( $result['question_wrong'] ); ?></p>
			</div>
		</li>
		<li class="result-statistic-field">
			<div class="result-field-wrapper">
				<label><?php echo esc_html_x( 'Skipped', 'quiz-result', 'learnpress' ); ?></label>
				<p><?php echo esc_html( $result['question_empty'] ); ?></p>
			</div>
		</li>
	</ul>
</div>
