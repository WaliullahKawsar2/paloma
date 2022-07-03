<?php
/** Do not allow directly accessing this file. */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' ); } ?>

<?php if ( ! empty( $terms ) ) : ?>
	<div class="adverts-flexbox adverts-categories-all">
	<?php foreach ( $terms as $term ) : ?>
		<?php $icon = adverts_taxonomy_get( 'advert_category', $term->term_id, 'advert_category_icon', 'folder' ); ?>
		<?php $count = adverts_category_post_count( $term ); ?>
		<?php $adverts_category_classes = 'adverts-flexbox-item ' . $columns . ' adverts-category-slug-' . $term->slug; ?>
		<div class="<?php beehive_add_reveal_animation( $adverts_category_classes ); ?>">
			<div class="adverts-flexbox-wrap all">
				<div class="adverts-category-all-main">
					<span class="category-icon background-primary <?php echo esc_attr( apply_filters( 'adverts_category_font_icon', 'adverts-icon-' . $icon, $term, 'small' ) ); ?>"></span>
					<?php do_action( 'adverts_category_pre_title', $term, 'small' ); ?>
					<h5 class="adverts-flexbox-title">
						<a class="" href="<?php echo esc_attr( get_term_link( $term ) ); ?>">
							<?php echo esc_html( $term->name ); ?>
							<?php if ( $show_count ) : ?>
								(<?php echo esc_html( $count ); ?>)
							<?php endif; ?>
						</a>
					</h5>
				</div>
				<ul class="adverts-flexbox-list">
					<?php
						$subs = get_terms(
							'advert_category',
							array(
								'hide_empty' => 0,
								'parent'     => $term->term_id,
								'number'     => $sub_count,
							)
						);
					?>
					<?php foreach ( $subs as $sub ) : ?>
					<li>
						<a href="<?php echo esc_attr( get_term_link( $sub ) ); ?>" class="light">
							<?php echo esc_html( $sub->name ); ?>
							<?php if ( $show_count ) : ?>
								<span class="count">(<?php echo esc_html( $sub->count ); ?>)</span>
							<?php endif; ?>
						</a>
					</li>
					<?php endforeach; ?>
					<?php if ( $count != 0 ) : ?>
						<li>
							<a href="<?php echo esc_attr( get_term_link( $term ) ); ?>" class="color-primary">
								<?php echo wp_kses_post( __( '<em>View All &raquo;</em>', 'wpadverts' ) ); ?>
							</a>
						</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	<?php endforeach; ?>
	</div>
<?php else : ?>
	<div class="adverts-grid-row">
		<div class="adverts-col-100">
			<span><?php esc_html_e( 'No categories found.', 'wpadverts' ); ?></span>
		</div>
	</div>
<?php endif; ?>
