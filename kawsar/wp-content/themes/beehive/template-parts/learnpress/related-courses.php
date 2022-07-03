<?php
/**
 * Related Adverts
 *
 * Displays related adverts under single ad
 * WP Advert plugin must be installed
 *
 * @package WordPress
 * @subpackage beehive
 * @since 1.2.5
 */

/** Do not allow directly accessing this file. */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' ); } ?>

<?php
if ( ! learn_press_is_course() ) {
	return; }
?>

<?php $related_courses = beehive_get_related_posts( get_the_ID(), 3, 'course_category' ); ?>
<?php if ( ! empty( $related_courses ) && $related_courses->have_posts() ) : ?>
	<div class="related-courses">
		<div class="block-title center">
			<h3><?php esc_html_e( 'Related Courses', 'beehive' ); ?></h3>
		</div>
		<ul class="learn-press-courses beehive-columns columns-3">
			<?php while ( $related_courses->have_posts() ) : ?>
				<?php $related_courses->the_post(); ?>
				<?php learn_press_get_template_part( 'content', 'course' ); ?>
			<?php endwhile; ?>
		</ul>
	</div>
<?php endif; ?>
<?php
wp_reset_postdata();
