<?php
/**
 * Template for displaying course content within the loop.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-single-course.php
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

/**
 * Deprecated actions.
 *
 * @deprecated
 */
do_action( 'learn_press_before_main_content' );
do_action( 'learn_press_before_single_course' );
do_action( 'learn_press_before_single_course_summary' );

/**
 * Before single course.
 *
 * @since 3.0.0
 */
do_action( 'learn-press/before-main-content' );

do_action( 'learn-press/before-single-course' );

?>
<div id="learn-press-course" class="course-summary">

	<div class="entry-header">
		<div class="row">
			<div class="single-course-title <?php echo ( in_array( beehive()->layout->get(), array( 'social', 'social-12', 'social-collapsed', 'full' ), true ) ) ? esc_attr( 'col-lg-8' ) : esc_attr( 'col-lg-12' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
				<?php the_title( '<h1 class="title h2">', '</h1>' ); ?>
			</div>
		</div>
	</div>

	<div class="single-course-meta">
		<div class="row">
			<div class="<?php echo ( in_array( beehive()->layout->get(), array( 'social', 'social-12', 'social-collapsed', 'full' ), true ) ) ? esc_attr( 'col-lg-8' ) : esc_attr( 'col-lg-12' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
				<div class="course-meta">
					<?php do_action( 'beehive_single_course_meta' ); ?>
				</div>
			</div>
			<?php if ( in_array( beehive()->layout->get(), array( 'social', 'social-12', 'social-collapsed', 'full' ), true ) ) : ?>
				<div class="col-lg-4">
					<div class="course-payment">
						<?php do_action( 'beehive_single_course_action' ); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="single-course-main">
		<div class="row">
			<div class="summery-column <?php echo ( in_array( beehive()->layout->get(), array( 'social', 'social-12', 'social-collapsed', 'full' ), true ) ) ? esc_attr( 'col-lg-8' ) : esc_attr( 'col-lg-12' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
				<?php learn_press_course_thumbnail(); ?>
				<?php
					/**
					 * Single summary hook.
					 *
					 * @since 3.0.0
					 * @see learn_press_single_course_summary()
					 */
					do_action( 'learn-press/single-course-summary' );
				?>
			</div>
			<?php if ( in_array( beehive()->layout->get(), array( 'social', 'social-12', 'social-collapsed', 'full' ), true ) ) : ?>
				<div class="features-column col-lg-4">
					<?php beehive_lp_single_course_features(); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

</div>
<?php
if ( beehive()->options->get( 'key=related-courses' ) ) :
	beehive_related_courses();
endif;

/**
 * After single course.
 *
 * @since 3.0.0
 */
do_action( 'learn-press/after-main-content' );

do_action( 'learn-press/after-single-course' );

/**
 * Deprecated actions.
 *
 * @deprecated
 */
do_action( 'learn_press_after_single_course_summary' );
do_action( 'learn_press_after_single_course' );
do_action( 'learn_press_after_main_content' );
