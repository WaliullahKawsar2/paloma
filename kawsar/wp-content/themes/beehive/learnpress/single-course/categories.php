<?php
/**
 * Template for displaying categories of single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/categories.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
?>

<?php $term_list = get_the_terms( get_the_ID(), 'course_category' ); ?>

<?php if ( $term_list && is_array( $term_list ) ) : ?>
	<?php if ( count( $term_list ) > 2 ) : ?>
		<div class="course-categories meta">
			<span class="label"><?php esc_html_e( 'Categories', 'beehive' ); ?></span>
			<?php $visible_terms = array_slice( $term_list, 0, 2 ); ?>
			<?php $hidden_terms = array_slice( $term_list, 2 ); ?>
			<span class="cat-links">
				<?php foreach ( $visible_terms as $term ): // @codingStandardsIgnoreLine ?>
					<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="<?php esc_html_e( 'tag', 'beehive' ); ?>"><?php echo esc_html( $term->name ); ?></a>
				<?php endforeach; ?>
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
				<span class="dropdown-menu dropdown-menu-right">
					<?php foreach ( $hidden_terms as $term ) : // @codingStandardsIgnoreLine  ?>
						<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="dropdown-item" rel="<?php esc_html_e( 'tag', 'beehive' ); ?>"><?php echo esc_html( $term->name ); ?></a>
					<?php endforeach; ?>
				</span>
			</span>
		</div>
	<?php else : ?>
		<div class="course-categories meta">
			<span class="label"><?php esc_html_e( 'Categories', 'beehive' ); ?></span>
			<?php printf( '<span class="cat-links">%s</span>', get_the_term_list( get_the_ID(), 'course_category', '', ', ', '' ) ); ?>
		</div>
	<?php endif; ?>
<?php endif; ?>
