<?php
/**
 * Course filters
 *
 * Renders filters on courses page
 *
 * @package WordPress
 * @subpackage beehive
 * @since 1.2.0
 */

/** Do not allow directly accessing this file. */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' ); } ?>

<div class="course-filters beehive-filters">
	<div class="filter-wrapper">
		<div class="search beehive-course-search">
			<?php learn_press_search_form(); ?>
		</div>
	</div>
</div>
