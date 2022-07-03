<?php
/**
 * Beehive activity widget
 * This class renders the latest activity widget
 *
 * @package    Beehive_Widgets
 * @subpackage Beehive_Widgets/includes
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Beehive_Widget_Latest_Activity class.
 *
 * @extends WP_Widget
 * @since 1.0.0
 */
class Beehive_Widget_Latest_Activity extends WP_Widget {
	/**
	 * Widget constructor
	 */
	public function __construct() {
		$widget_options = array(
			'classname'   => 'beehive-activity-widget buddypress',
			'description' => __( 'Shows activity list', 'beehive-widgets' ),
		);
		$name           = __( '(Beehive), Activity List', 'beehive-widgets' );
		parent::__construct( 'Beehive_Widget_Latest_Activity', $name, $widget_options );
	}

	/**
	 * Display widget in the front-end
	 *
	 * @param array $args args.
	 * @param array $instance widget instance.
	 */
	public function widget( $args, $instance ) {

		// Limit.
		$limit = $instance['max'];

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo $args['before_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		if ( ! empty( $instance['title'] ) ) {
			echo esc_html( $instance['title'] );
		} else {
			echo esc_html__( 'Recent activity', 'beehive-widgets' );
		}
		echo $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>

		<?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) . '&max=' . $limit ) ) : ?>
			<ul class="widget-activity-list">
					<?php
					while ( bp_activities() ) :
						bp_the_activity();
						?>
				<li class="<?php bp_activity_css_class(); ?>" id="activity-<?php bp_activity_id(); ?>">
						<?php bp_activity_action( array( 'no_timestamp' => true ) ); ?>
				<span class="activity mute"><?php echo bp_core_time_since( bp_get_activity_date_recorded() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</li>
			<?php endwhile; ?>
			</ul>
		<?php else : ?>
			<div class="alert alert-warning" role="alert">
				<?php esc_html_e( 'No activity found!', 'beehive-widgets' ); ?>
			</div>
		<?php endif; ?>

		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Update widget settings.
	 *
	 * @param array $new_instance new instance.
	 * @param array $old_instance old instance.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		$instance          = $old_instance;
		$instance['title'] = esc_attr( $new_instance['title'] );
		$instance['max']   = $new_instance['max'];

		return $instance;
	}

	/**
	 * Display widget settings (back-end form)
	 *
	 * @param array $instance current instance.
	 * @return void
	 */
	public function form( $instance ) {
		$defaults = array(
			'title' => __( 'Recent activity', 'beehive-widgets' ),
			'max'   => 5,
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="beehive-member-activity-title"><?php esc_html_e( 'Title:', 'beehive-widgets' ); ?></label>
			<input class="widefat" id="beehive-member-activity-title" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'max' ) ); ?>"><?php esc_html_e( 'Max activity to show:', 'beehive-widgets' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'max' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'max' ) ); ?>" value="<?php echo absint( $instance['max'] ); ?>" type="number" min="1" step="1" size="3"/>
		</p>
		<?php
	}
}

/**
 * Register widget
 */
function register_beehive_widget_latest_activity() {
	register_widget( 'Beehive_Widget_Latest_Activity' );
}
add_action( 'widgets_init', 'register_beehive_widget_latest_activity' );
