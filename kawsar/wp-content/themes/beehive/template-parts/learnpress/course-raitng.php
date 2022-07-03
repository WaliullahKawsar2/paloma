<?php
/**
 * Course ratinsg
 *
 * @package WordPress
 * @subpackage beehive
 * @since 1.2.5
 */

/** Do not allow directly accessing this file. */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' ); } ?>

<?php
if ( ! class_exists( 'LP_Addon_Course_Review' ) || ! get_the_ID() ) {
	return;
}
$course_rate = learn_press_get_course_rate( get_the_ID() );
?>

<div class="beehive-course-rating">
	<div class="review-stars-rated">
		<ul class="review-stars">
			<li><span class="ion-android-star-outline"></span></li>
			<li><span class="ion-android-star-outline"></span></li>
			<li><span class="ion-android-star-outline"></span></li>
			<li><span class="ion-android-star-outline"></span></li>
			<li><span class="ion-android-star-outline"></span></li>
		</ul>
		<ul class="review-stars filled"
			style="<?php echo esc_attr( 'width: ' . $course_rate * 20 . '%;' ); ?>">
			<li><span class="ion-android-star"></span></li>
			<li><span class="ion-android-star"></span></li>
			<li><span class="ion-android-star"></span></li>
			<li><span class="ion-android-star"></span></li>
			<li><span class="ion-android-star"></span></li>
		</ul>
	</div>
	<span class="count">(<?php echo esc_html( learn_press_get_course_rate_total( get_the_ID() ) ); ?>)</span>
</div>
