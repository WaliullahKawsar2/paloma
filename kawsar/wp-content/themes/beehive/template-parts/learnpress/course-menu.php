<?php
/**
 * Course menu
 *
 * This template renders course menu for beehive theme
 *
 * @package WordPress
 * @subpackage beehive
 * @since 1.2.0
 */

/** Do not allow directly accessing this file. */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' ); } ?>

<?php
wp_nav_menu(
	array(
		'theme_location'  => 'course_menu',
		'depth'           => 1,
		'container'       => 'nav',
		'container_class' => 'nav-component',
		'menu_class'      => 'nav-component-list course-navbar',
		'fallback_cb'     => 'Beehive_Navwalker::fallback',
	)
);
