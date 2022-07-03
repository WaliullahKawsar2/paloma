<?php
/**
 * Job categories
 * This template renders job categories shortcode
 *
 * @package WordPress
 */

/** Do not allow directly accessing this file. */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' ); } ?>

<?php if ( ! empty( $terms ) ) : ?>
	<ul class="<?php echo esc_attr( apply_filters( 'beehive_job_categories_list_classes', join( ' ', $listing_classes ) ) ); ?>">
	<?php foreach ( $terms as $term ) : // @codingStandardsIgnoreLine ?>
		<li class="job-category-item column <?php echo esc_attr( 'job-category-slug-' . $term->slug ); ?>">
			<div class="item-wrap">
				<h5 class="title top-category">
					<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="jobs-category-link ellipsis">
						<span class="cat-text"><?php echo esc_html( $term->name ); ?></span>
						<?php if ( $params['show_count'] ) : ?>
							<span class="count"><?php echo '&#40;' . esc_html( $term->count ) . '&#41;'; ?></span>
						<?php endif; ?>
					</a>
				</h5>
				<?php if ( 'all' === $show ) : ?>
					<?php
						$child_terms = get_terms(
							'job_listing_category',
							array(
								'hide_empty' => 0,
								'parent'     => $term->term_id,
								'number'     => $params['child_count'],
							)
						);
					?>
					<ul class="job-category-child-listings">
						<?php foreach ( $child_terms as $child_term ) : ?>
						<li class="job-category-child-item <?php echo esc_attr( 'job-category-slug-' . $child_term->slug ); ?>">
							<a href="<?php echo esc_url( get_term_link( $child_term ) ); ?>" class="light">
								<span class="cat-text"><?php echo esc_html( $child_term->name ); ?></span>
								<?php if ( $params['show_count'] ) : ?>
									<span class="count"><?php echo '&#40;' . esc_html( $child_term->count ) . '&#41;'; ?></span>
								<?php endif; ?>
							</a>
						</li>
						<?php endforeach; ?>
						<?php if ( $term->count ) : ?>
						<li>
							<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="color-primary">
								<?php esc_html_e( 'View All', 'beehive-job-categories' ); ?>
							</a>
						</li>
						<?php endif; ?>
					</ul>
				<?php endif; ?>
			</div>
		</li>
	<?php endforeach; ?>
	</ul>
<?php else : ?>
	<div class="no-categories-found">
		<span><?php esc_html_e( 'No job categories found.', 'beehive-job-categories' ); ?></span>
	</div>
<?php endif; ?>
