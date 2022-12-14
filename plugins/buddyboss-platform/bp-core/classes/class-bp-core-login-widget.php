<?php
/**
 * BuddyBoss Core Login Widget.
 *
 * @package BuddyBoss\Core
 * @since BuddyPress 1.9.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * BuddyPress Login Widget.
 *
 * @since BuddyPress 1.9.0
 */
class BP_Core_Login_Widget extends WP_Widget {

	/**
	 * Constructor method.
	 *
	 * @since BuddyPress 1.9.0
	 */
	public function __construct() {
		parent::__construct(
			false,
			__( '(BB) Log In', 'buddyboss' ),
			array(
				'description'                 => __( 'Show a Log In form to logged-out visitors, and a Log Out link to those who are logged in.', 'buddyboss' ),
				'classname'                   => 'widget_bp_core_login_widget buddypress widget',
				'customize_selective_refresh' => true,
			)
		);
	}

	/**
	 * Display the login widget.
	 *
	 * @since BuddyPress 1.9.0
	 *
	 * @see WP_Widget::widget() for description of parameters.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Widget settings, as saved by the user.
	 */
	public function widget( $args, $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';

		/**
		 * Filters the title of the Login widget.
		 *
		 * @since BuddyPress 1.9.0
		 * @since BuddyPress 2.3.0 Added 'instance' and 'id_base' to arguments passed to filter.
		 *
		 * @param string $title    The widget title.
		 * @param array  $instance The settings for the particular instance of the widget.
		 * @param string $id_base  Root ID for all widgets of this type.
		 */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget'];

		echo $args['before_title'] . esc_html( $title ) . $args['after_title']; ?>

		<?php if ( is_user_logged_in() ) : ?>

			<?php
			/**
			 * Fires before the display of widget content if logged in.
			 *
			 * @since BuddyPress 1.9.0
			 */
			do_action( 'bp_before_login_widget_loggedin' );
			?>

			<div class="bp-login-widget-user-avatar">
				<a href="<?php echo bp_loggedin_user_domain(); ?>">
					<?php bp_loggedin_user_avatar( 'type=thumb&width=50&height=50' ); ?>
				</a>
			</div>

			<div class="bp-login-widget-user-links">
				<h4 class="bp-login-widget-user-link"><?php echo bp_core_get_userlink( bp_loggedin_user_id() ); ?></h4>
				<div class="bp-login-widget-user-logout"><a class="logout" href="<?php echo wp_logout_url( bp_get_requested_url() ); ?>"><?php _e( 'Log Out', 'buddyboss' ); ?></a></div>
			</div>

			<?php

			/**
			 * Fires after the display of widget content if logged in.
			 *
			 * @since BuddyPress 1.9.0
			 */
			do_action( 'bp_after_login_widget_loggedin' );
			?>

		<?php else : ?>

			<?php

			/**
			 * Fires before the display of widget content if logged out.
			 *
			 * @since BuddyPress 1.9.0
			 */
			do_action( 'bp_before_login_widget_loggedout' );
			?>

			<form name="bp-login-form" id="bp-login-widget-form" class="standard-form" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
				<label for="bp-login-widget-user-login"><?php _e( 'Username', 'buddyboss' ); ?></label>
				<input type="text" name="log" id="bp-login-widget-user-login" class="input" value="" />

				<label for="bp-login-widget-user-pass"><?php _e( 'Password', 'buddyboss' ); ?></label>
				<input type="password" name="pwd" id="bp-login-widget-user-pass" class="input" value="" <?php bp_form_field_attributes( 'password' ); ?> />

				<div class="forgetmenot">
					<input name="rememberme" type="checkbox" id="bp-login-widget-rememberme" class="bs-styled-checkbox" value="forever" />
					<label for="bp-login-widget-rememberme"><?php _e( 'Remember Me', 'buddyboss' ); ?></label>
				</div>

				<div class="bbp-submit-wrapper">
					<input type="submit" name="wp-submit" id="bp-login-widget-submit" value="<?php esc_attr_e( 'Log In', 'buddyboss' ); ?>" />
				</div>

				<?php if ( bp_get_signup_allowed() ) : ?>

					<span class="bp-login-widget-register-link"><a href="<?php echo esc_url( bp_get_signup_page() ); ?>"><?php _e( 'Register', 'buddyboss' ); ?></a></span>

				<?php endif; ?>

				<?php

				/**
				 * Fires inside the display of the login widget form.
				 *
				 * @since BuddyPress 2.4.0
				 */
				do_action( 'bp_login_widget_form' );
				?>

			</form>

			<?php

			/**
			 * Fires after the display of widget content if logged out.
			 *
			 * @since BuddyPress 1.9.0
			 */
			do_action( 'bp_after_login_widget_loggedout' );
			?>

			<?php
		endif;

		echo $args['after_widget'];
	}

	/**
	 * Update the login widget options.
	 *
	 * @since BuddyPress 1.9.0
	 *
	 * @param array $new_instance The new instance options.
	 * @param array $old_instance The old instance options.
	 * @return array $instance The parsed options to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = isset( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

	/**
	 * Output the login widget options form.
	 *
	 * @since BuddyPress 1.9.0
	 *
	 * @param array $instance Settings for this widget.
	 * @return void
	 */
	public function form( $instance = array() ) {

		$settings = bp_parse_args(
			$instance,
			array(
				'title' => '',
			)
		);
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'buddyboss' ); ?>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>" /></label>
		</p>

		<?php
	}
}
