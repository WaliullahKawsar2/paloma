<?php
/**
 * Template for displaying course rate.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/addons/course-review/course-rate.php.
 *
 * @author ThimPress
 * @package LearnPress/Course-Review/Templates
 * version  3.0.1
 */

// Prevent loading this file directly.
defined( 'ABSPATH' ) || exit;

$course_id       = get_the_ID();
$course_rate_res = learn_press_get_course_rate( $course_id, false );
$course_rate     = $course_rate_res['rated'];
$total           = $course_rate_res['total'];

// translators: number of ratings.
$total_rating = $total ? sprintf( _n( '%1$s rating', '%1$s ratings', $total, 'beehive' ), number_format_i18n( $total ) ) : esc_html__( '0 rating', 'beehive' );
?>

<div class="course-rate course-rate-wrapper">

	<div class="average-rating">
		<div class="average-value h1 color-primary">
			<?php echo ( isset( $course_rate ) && $course_rate ) ? esc_html( round( $course_rate, 1 ) ) : 0; ?>
		</div>
		<div class="star-rating">
			<?php beehive_lp_course_rating_stars(); ?>
		</div>
		<div class="review-numbers">
			<?php echo esc_html( $total_rating ); ?>
		</div>
	</div>

	<div class="detailed-rating">
		<?php
		if ( isset( $course_rate_res['items'] ) && ! empty( $course_rate_res['items'] ) ) :
			foreach ( $course_rate_res['items'] as $item ) :
				?>
				<div class="course-rate">
					<span><?php echo esc_html( $item['rated'] ); ?><?php esc_html_e( ' Star', 'learnpress-course-review' ); ?></span>
					<div class="review-bar">
						<div class="rating" style="width: <?php echo esc_attr( $item['percent'] ); ?>%;"></div>
					</div>
					<span><?php echo esc_html( $item['percent'] ); ?>%</span>
				</div>
				<?php
			endforeach;
		endif;
		?>
	</div>
</div>
