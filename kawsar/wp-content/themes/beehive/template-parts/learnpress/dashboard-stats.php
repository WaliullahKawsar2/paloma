<?php
/**
 * Dashboard statistics
 *
 * Displays users stats on lp profile page
 *
 * @package WordPress
 * @subpackage beehive
 * @since 1.2.0
 */

/** Do not allow directly accessing this file. */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' ); } ?>

<div class="user-dashboard-statistics">
	<ul class="stats clearfix">
		<li class="item">
			<div class="item-wrapper">
				<span class="count color-primary h2"><?php echo esc_html( $courses_completed ); ?></span>
				<span class="item-name"><?php esc_html_e( 'Courses completed', 'beehive' ); ?></span>
			</div>
		</li>
		<li class="item">
			<div class="item-wrapper">
				<span class="count color-primary h2"><?php echo esc_html( $courses_inprogress ); ?></span>
				<span class="item-name"><?php esc_html_e( 'Courses in progress', 'beehive' ); ?></span>
			</div>
		</li>
		<li class="item">
			<div class="item-wrapper">
				<span class="count color-primary h2"><?php echo esc_html( $quizzes_completed ); ?></span>
				<span class="item-name"><?php esc_html_e( 'Quizzes Completed', 'beehive' ); ?></span>
			</div>
		</li>
		<li class="item">
			<div class="item-wrapper">
				<span class="count color-primary h2"><?php echo esc_html( $quizzes_inprogress ); ?></span>
				<span class="item-name"><?php esc_html_e( 'Quizzes in progress', 'beehive' ); ?></span>
			</div>
		</li>
	</ul>
</div>
