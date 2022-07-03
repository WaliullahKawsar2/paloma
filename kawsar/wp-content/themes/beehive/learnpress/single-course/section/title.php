<?php
/**
 * Template for displaying title of section in single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/section/title.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.1
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$user        = learn_press_get_current_user();
$course      = learn_press_get_the_course();
$user_course = $user->get_course_data( get_the_ID() );

if ( ! isset( $section ) ) {
	return;
}

$title = $section->get_title(); // @codingStandardsIgnoreLine 
?>

<?php if ( $title ) : ?>
    <div class="section-header">
        <h5 class="section-title">
            <?php echo esc_html( $title ); ?>
            <span class="section-meta">
                <?php if ( $user->has_enrolled_course( $section->get_course_id() ) ) : ?>
                    <?php // translators: curriculam header. ?>
                    <span class="step color-primary"><?php printf( wp_kses_post( __( '%1$d/%2$d', 'learnpress' ) ), esc_html( $user_course->get_completed_items( '', false, $section->get_id() ) ), esc_html( $section->count_items( '', false ) ) ); ?></span>
                <?php else : ?>
                    <?php // translators: curriculam lessons and quizzes count. ?>
                    <span class="step color-primary"><?php echo esc_html( $section->count_items( '', false ) ); ?></span>
                <?php endif; ?>
            </span>
        </h5>
    </div>
    <?php if ( $section->get_description() ) : ?>
        <p class="section-desc"><?php echo esc_html( $section->get_description() ); ?></p>
    <?php endif; ?>
<?php endif; ?>
