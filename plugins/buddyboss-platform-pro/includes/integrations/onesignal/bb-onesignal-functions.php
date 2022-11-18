<?php
/**
 * OneSignal integration helpers
 *
 * @since   2.0.3
 * @package BuddyBoss\OneSignal
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Returns OneSignal Integration path.
 *
 * @since 2.0.3
 *
 * @param string $path Path to onesignal integration.
 */
function bb_onesignal_integration_path( $path = '' ) {
	return trailingslashit( bb_platform_pro()->integration_dir ) . 'onesignal/' . trim( $path, '/\\' );
}

/**
 * Returns OneSignal Integration url.
 *
 * @since 2.0.3
 *
 * @param string $path Path to onesignal integration.
 */
function bb_onesignal_integration_url( $path = '' ) {
	return trailingslashit( bb_platform_pro()->integration_url ) . 'onesignal/' . trim( $path, '/\\' );
}

/**
 * Checks if OneSignal is enabled.
 *
 * @since 2.0.3
 *
 * @param int $default Default option for OneSignal enable or not.
 *
 * @return bool Is OneSignal enabled or not.
 */
function bb_onesignal_is_enabled( $default = 1 ) {
	return (bool) apply_filters( 'bb_onesignal_is_enabled', (bool) bp_get_option( 'bb-onesignal-enable', $default ) );
}

/**
 * Link to OneSignal Settings tutorial.
 *
 * @since 2.0.3
 */
function bb_onesignal_settings_tutorial() {
	?>
	<p>
		<a class="button" href="
		<?php
		echo esc_url(
			bp_get_admin_url(
				add_query_arg(
					array(
						'page'    => 'bp-help',
						'article' => '125638',
					),
					'admin.php'
				)
			)
		);
		?>
		"><?php esc_html_e( 'View Tutorial', 'buddyboss-pro' ); ?></a>
	</p>
	<?php
}

/**
 * Get OneSignal Auth Key.
 *
 * @since 2.0.3
 *
 * @param string $default Default option for auth key.
 *
 * @return mixed|void OneSignal Auth Key.
 */
function bb_onesignal_auth_key( $default = '' ) {
	return apply_filters( 'bb_onesignal_auth_key', bp_get_option( 'bb-onesignal-auth-key', $default ) );
}

/**
 * Get OneSignal account apps.
 *
 * @since 2.0.3
 *
 * @param array $default Default option apps.
 *
 * @return mixed|void OneSignal apps.
 */
function bb_onesignal_account_apps( $default = array() ) {
	return apply_filters( 'bb_onesignal_account_apps', bp_get_option( 'bb-onesignal-account-apps', $default ) );
}

/**
 * Get OneSignal connected app.
 *
 * @since 2.0.3
 *
 * @param string $default Default connected app.
 *
 * @return mixed|void OneSignal connected app id.
 */
function bb_onesignal_connected_app( $default = '' ) {
	return apply_filters( 'bb_onesignal_connected_app', bp_get_option( 'bb-onesignal-connected-app', $default ) );
}

/**
 * Get OneSignal connected app name.
 *
 * @since 2.0.3
 *
 * @param string $default Default connected app name.
 *
 * @return mixed|void OneSignal connected app name.
 */
function bb_onesignal_connected_app_name( $default = '' ) {
	return apply_filters( 'bb_onesignal_connected_app_name', bp_get_option( 'bb-onesignal-connected-app-name', $default ) );
}

/**
 * Get OneSignal connected app details.
 *
 * @since 2.0.3
 *
 * @param array $default Default connected app details.
 *
 * @return mixed|void OneSignal connected app details.
 */
function bb_onesignal_connected_app_details( $default = array() ) {
	return apply_filters( 'bb_onesignal_connected_app_details', bp_get_option( 'bb-onesignal-connected-app-details', $default ) );
}

/**
 * Get oneSignal app rest api key.
 *
 * @since 2.0.3
 *
 * @return mixed|string
 */
function bb_onesignal_connected_app_rest_api_key() {
	$settings = bb_onesignal_connected_app_details();

	return isset( $settings['basic_auth_key'] ) ? $settings['basic_auth_key'] : '';
}

/**
 * Fetch the selected app details and store into DB.
 *
 * @since 2.0.3
 *
 * @return void
 */
function bb_onesignal_update_app_details() {
	$auth_key = bb_onesignal_auth_key();
	$app_id   = bb_onesignal_connected_app();

	if ( ! empty( $auth_key ) && ! empty( $app_id ) ) {
		$args = array(
			'sslverify' => false,
			'headers'   => array( 'Authorization' => 'Basic ' . $auth_key ),
		);

		$url     = bb_onesignal_api_endpoint() . 'apps/' . $app_id;
		$request = bbpro_remote_get( $url, $args );

		$web_url  = wp_parse_url( site_url() );
		$site_url = ( isset( $web_url['scheme'] ) && $web_url['host'] ) ? $web_url['scheme'] . '://' . $web_url['host'] : site_url();

		$response      = wp_remote_retrieve_body( $request );
		$response      = json_decode( $response, true );
		$response_code = wp_remote_retrieve_response_code( $request );

		if ( 200 === $response_code && ! empty( $response ) ) {
			if ( ! empty( $web_url['scheme'] ) && 'http' === $web_url['scheme'] && empty( $response['chrome_web_sub_domain'] ) ) {
				$error = sprintf(
					/* translators: 1. App name 2. App configuration URL */
					__( 'The %1$s app is currently set to only support secure (HTTPS) sites. Please update this app\'s %2$s in OneSignal or create a new app for this site.', 'buddyboss-pro' ),
					'<strong>' . $response['name'] . ' </strong>',
					'<a href="' . esc_url( 'https://app.onesignal.com/apps/' . $response['id'] . '/settings/webpush/configure' ) . '" target="_blank">' . __( 'configuration', 'buddyboss-pro' ) . '</a>'
				);
				set_transient( 'bb_onesignal_warning', $error, HOUR_IN_SECONDS );
				bp_update_option( 'bb-onesignal-connected-app', $response['id'] );
				bp_update_option( 'bb-onesignal-connected-app-name', $response['name'] );
				bp_delete_option( 'bb-onesignal-connected-app-details' );
			} elseif (
				empty( $response['safari_site_origin'] ) ||
				empty( $response['chrome_web_origin'] ) ||
				untrailingslashit( $response['safari_site_origin'] ) !== untrailingslashit( $site_url ) ||
				untrailingslashit( $response['chrome_web_origin'] ) !== untrailingslashit( $site_url )
			) {
				$error = sprintf(
					/* translators: 1. App name 2. App configuration URL */
					__( 'The Site URL for the %1$s app does not match this site\'s url. Please update this app\'s %2$s in OneSignal or create a new app for this site.', 'buddyboss-pro' ),
					'<strong>' . $response['name'] . ' </strong>',
					'<a href="' . esc_url( 'https://app.onesignal.com/apps/' . $response['id'] . '/settings/webpush/configure' ) . '" target="_blank">' . __( 'configuration', 'buddyboss-pro' ) . '</a>'
				);
				set_transient( 'bb_onesignal_warning', $error, HOUR_IN_SECONDS );
				bp_update_option( 'bb-onesignal-connected-app', $response['id'] );
				bp_update_option( 'bb-onesignal-connected-app-name', $response['name'] );
				bp_delete_option( 'bb-onesignal-connected-app-details' );
			} else {
				bp_update_option( 'bb-onesignal-connected-app', $response['id'] );
				bp_update_option( 'bb-onesignal-connected-app-name', $response['name'] );
				bp_update_option( 'bb-onesignal-connected-app-details', $response );
			}
		} else {
			bp_delete_option( 'bb-onesignal-connected-app' );
			bp_delete_option( 'bb-onesignal-connected-app-name' );
			bp_delete_option( 'bb-onesignal-connected-app-details' );
		}
	}
}

/**
 * OneSignal Create App Endpoint.
 *
 * @since 2.0.3
 *
 * @return string
 */
function bb_onesignal_api_endpoint() {
	return 'https://onesignal.com/api/v1/';
}

/**
 * OneSignal View App Endpoint.
 *
 * @since 2.0.3
 *
 * @param int $app_id App ID.
 *
 * @return string
 */
function bb_onesignal_view_app_endpoint( $app_id ) {
	return bb_onesignal_api_endpoint() . 'apps/' . $app_id;
}

/**
 * OneSignal Create Notification Endpoint.
 *
 * @since 2.0.3
 *
 * @return string
 */
function bb_onesignal_notification_endpoint() {
	return bb_onesignal_api_endpoint() . 'notifications';
}

/**
 * OneSignal View Notification Endpoint.
 *
 * @since 2.0.3
 *
 * @param int $notification_id Notification ID.
 * @param int $app_id          App ID.
 *
 * @return string
 */
function bb_onesignal_view_notification_endpoint( $notification_id, $app_id ) {
	return bb_onesignal_notification_endpoint() . '/' . $notification_id . '?app_id=' . $app_id;
}

/**
 * OneSignal validate auth.
 *
 * @since 2.0.3
 *
 * @return boolean
 */
function bb_onesignal_validate_auth_key() {
	return (bool) ( ! empty( bb_onesignal_auth_key() ) && ! empty( bb_onesignal_account_apps() ) );
}

/**
 * OneSignal validate app.
 *
 * @since 2.0.3
 *
 * @return boolean
 */
function bb_onesignal_validate_app() {
	return (bool) bb_onesignal_validate_auth_key() && ! empty( bb_onesignal_connected_app() ) && ! empty( bb_onesignal_connected_app_name() );
}

/**
 * OneSignal Web Push enabled or not.
 *
 * @since 2.0.3
 *
 * @param bool $default Default its enabled or not.
 *
 * @return bool
 */
function bb_onesignal_enabled_web_push( $default = 0 ) {
	return (bool) bb_onesignal_validate_app() && apply_filters( 'bb_onesignal_enabled_web_push', bp_get_option( 'bb-onesignal-enabled-web-push', $default ) );
}

/**
 * OneSignal Web Push default notification icon.
 *
 * @since 2.0.3
 *
 * @param bool $default Default notification icon.
 *
 * @return string
 */
function bb_onesignal_default_notification_icon( $default = '' ) {
	return (string) apply_filters( 'bb_onesignal_default_notification_icon', bp_get_option( 'bb-onesignal-default-notification-icon', $default ) );
}

/**
 * OneSignal automatically request permission enabled or not.
 *
 * @since 2.0.3
 *
 * @param bool $default Default its enabled or not.
 *
 * @return bool
 */
function bb_onesignal_request_permission( $default = 0 ) {
	return (bool) bb_onesignal_enabled_web_push() && apply_filters( 'bb_onesignal_request_permission', bp_get_option( 'bb-onesignal-request-permission', $default ) );
}

/**
 * OneSignal automatic request validate.
 *
 * @since 2.0.3
 *
 * @param string $default Default value by 'visit'.
 *
 * @return string
 */
function bb_onesignal_permission_validate( $default = 'visit' ) {
	return apply_filters( 'bb_onesignal_permission_validate', bp_get_option( 'bb-onesignal-permission-validate', $default ) );
}

/**
 * OneSignal soft prompt enable or not.
 *
 * @since 2.0.3
 *
 * @param bool $default Default its enabled or not.
 *
 * @return bool
 */
function bb_onesignal_enable_soft_prompt( $default = 0 ) {
	return (bool) bb_onesignal_enabled_web_push() && apply_filters( 'bb_onesignal_enable_soft_prompt', bp_get_option( 'bb-onesignal-enable-soft-prompt', $default ) );
}

/**
 * Get soft prompt allow button text.
 *
 * @since 2.0.3
 *
 * @return mixed|void soft prompt allow button text.
 */
function bb_onesignal_soft_prompt_allow_btn_text() {
	return apply_filters( 'bb_onesignal_soft_prompt_allow_btn_text', bp_get_option( 'bb-onesignal-enable-soft-prompt-allow-button', '' ) );
}

/**
 * Get soft prompt allow button placeholder text.
 *
 * @since 2.0.3
 *
 * @return mixed|void soft prompt allow button text.
 */
function bb_onesignal_soft_prompt_allow_btn_placeholder_text() {
	return esc_html__( 'Allow', 'buddyboss-pro' );
}

/**
 * Get soft prompt cancel button text.
 *
 * @since 2.0.3
 *
 * @return mixed|void soft prompt cancel button text.
 */
function bb_onesignal_soft_prompt_cancel_btn_text() {
	return apply_filters( 'bb_onesignal_soft_prompt_cancel_btn_text', bp_get_option( 'bb-onesignal-enable-soft-prompt-cancel-button', '' ) );
}

/**
 * Get soft prompt cancel button text placeholder.
 *
 * @since 2.0.3
 *
 * @return mixed|void soft prompt cancel button text.
 */
function bb_onesignal_soft_prompt_cancel_btn_placeholder_text() {
	return esc_html__( 'No Thanks', 'buddyboss-pro' );
}

/**
 * Get soft prompt message text.
 *
 * @since 2.0.3
 *
 * @return mixed|void soft prompt message text.
 */
function bb_onesignal_soft_prompt_message_text() {
	return apply_filters( 'bb_onesignal_soft_prompt_message_text', bp_get_option( 'bb-onesignal-enable-soft-prompt-message', '' ) );
}

/**
 * Get soft prompt message text placeholder.
 *
 * @since 2.0.3
 *
 * @return mixed|void soft prompt message text.
 */
function bb_onesignal_soft_prompt_message_placeholder_text() {
	return esc_html__( 'Subscribe to push notifications to keep up to date with the latest activity on this site.', 'buddyboss-pro' );
}

/**
 * Get default soft prompt upload avatar URL.
 *
 * @since 2.0.3
 *
 * @param string $default Optional. Fallback value if not found in the database.
 *                        Default: Empty string.
 * @param string $size    This parameter specifies whether you'd like the 'full' or 'thumb' avatar. Default: 'full'.
 * @return string Return default custom upload avatar URL.
 */
function bb_onesignal_soft_prompt_image( $default = '', $size = 'full' ) {

	$custom_avatar_url = bp_get_option( 'bb-onesignal-enable-soft-prompt-image', $default );

	/**
	 * Filters to change default custom upload avatar image.
	 *
	 * @since 2.0.3
	 *
	 * @param string $custom_upload_profile_avatar Default custom upload avatar URL.
	 * @param string $size                         This parameter specifies whether you'd like the 'full' or 'thumb' avatar.
	 */
	return apply_filters( 'bb_onesignal_soft_prompt_image', $custom_avatar_url, $size );
}

/**
 * Callback fields for the push notification fields options.
 *
 * @since 2.0.3
 *
 * @return void
 */
function bb_onesignal_admin_setting_callback_push_notification_fields() {
	$validate_auth = ! bb_onesignal_validate_auth_key();
	$invalid_app   = ! bb_onesignal_validate_app();

	$disabled = $validate_auth || $invalid_app;
	$checked  = bb_onesignal_enabled_web_push();
	?>
	<input id="bb-onesignal-enabled-web-push" name="bb-onesignal-enabled-web-push" type="checkbox" value="1"
		<?php
		checked( $checked );
		disabled( $disabled );
		?>
	/>
	<label for="bb-onesignal-enabled-web-push"><?php esc_html_e( 'Allow members to subscribe to notifications through their browser', 'buddyboss-pro' ); ?></label>
	<p class="description"><?php esc_html_e( 'Once enabled, members will be able to opt-in to receive all BuddyBoss Notifications as push notifications through their web browser.', 'buddyboss-pro' ); ?></p>
	<?php

	if ( $validate_auth ) {
		?>
		<div class="bp-messages-feedback">
			<div class="bp-feedback warning">
				<span class="bp-icon" aria-hidden="true"></span>
				<p>
					<?php
					printf(
						wp_kses_post(
						/* translators: 1. OneSignal error type. 2. Admin integration url. */
							__( 'Please enter a valid %1$s in the %2$s settings.', 'buddyboss-pro' )
						),
						'<strong>' . esc_html__( 'OneSignal Auth Key', 'buddyboss-pro' ) . '</strong>',
						'<a href="' .
						esc_url(
							bp_get_admin_url(
								add_query_arg(
									array(
										'page' => 'bp-integrations',
										'tab'  => 'bb-onesignal',
									),
									'admin.php'
								)
							)
						)
						. '">' . esc_html__( 'Integration', 'buddyboss-pro' ) . '</a>'
					);
					?>
				</p>
			</div>
		</div>
		<?php
	} elseif ( $invalid_app ) {
		?>
		<div class="bp-messages-feedback">
			<div class="bp-feedback warning">
				<span class="bp-icon" aria-hidden="true"></span>
				<p>
					<?php
					printf(
						wp_kses_post(
						/* translators: 1. OneSignal error type. 2. Admin integration url. */
							__( 'Please select a valid %1$s in the %2$s settings.', 'buddyboss-pro' )
						),
						'<strong>' . esc_html__( 'OneSignal App', 'buddyboss-pro' ) . '</strong>',
						'<a href="' .
						esc_url(
							bp_get_admin_url(
								add_query_arg(
									array(
										'page' => 'bp-integrations',
										'tab'  => 'bb-onesignal',
									),
									'admin.php'
								)
							)
						)
						. '">' . esc_html__( 'Integration', 'buddyboss-pro' ) . '</a>'
					);
					?>
				</p>
			</div>
		</div>
		<?php
	}
}

/**
 * Callback fields for the default web push notification icon fields options.
 *
 * @since 2.0.3
 *
 * @return void
 */
function bb_onesignal_admin_setting_callback_default_notification_icon_fields() {
	$hide_show_style           = 'bp-inline-block';
	$default_notification_icon = bb_onesignal_default_notification_icon();

	if ( ! $default_notification_icon ) {
		$hide_show_style = 'bp-hide';
	}
	?>

	<div class="bb-default-custom-upload-file bbpro-upload-attachment" data-object="notification">
		<div class="bb-upload-container bbpro-attachment-upload-container">
			<img src="<?php echo esc_url( $default_notification_icon ); ?>" class="bb-upload-preview notification-0-avatar <?php echo esc_attr( $hide_show_style ); ?>" >
			<input type="hidden" name="bb-onesignal-default-notification-icon" class="bb-default-custom-avatar-field" value="<?php echo esc_url( bb_onesignal_default_notification_icon() ); ?>">
		</div>
		<div class="bb-img-button-wrap">
			<a href="#TB_inline?width=800px&height=400px&inlineId=bbpro-notification-icon-editor" class="button button-large thickbox bb-attachment-user-edit" data-uploading="<?php esc_html_e( 'Uploading...', 'buddyboss-pro' ); ?>" data-upload="<?php esc_html_e( 'Upload', 'buddyboss-pro' ); ?>"><?php esc_html_e( 'Upload', 'buddyboss-pro' ); ?></a>
			<a href="#" class="delete button button-large bbpro-img-remove-button  <?php echo esc_attr( $hide_show_style ); ?>" data-removing="<?php esc_html_e( 'Removing...', 'buddyboss-pro' ); ?>" data-remove="<?php esc_html_e( 'Remove', 'buddyboss-pro' ); ?>"><?php esc_html_e( 'Remove', 'buddyboss-pro' ); ?></a>
			<div id="bbpro-notification-icon-editor" style="display:none;">
				<?php bp_attachments_get_template_part( 'avatars/index' ); ?>
			</div>
		</div>
		<div class="bp-messages-feedback admin-notice bbpro-attachment-status" style="display: none;">
			<div class="bp-feedback">
				<p class="bbpro-attachment-upload-feedback"></p>
			</div>
		</div>
	</div>
	<p class="description">
		<?php
		echo sprintf(
		/* translators: 1: Notification icon width in pixels. 2: Notification icon height in pixels */
			esc_html__( 'Upload an image to be the default icon used for web push notifications. Certain notification types may use different icons. The recommended size is %1$spx by %2$spx.', 'buddyboss-pro' ),
			absint( bp_core_avatar_full_width() ),
			absint( bp_core_avatar_full_height() )
		);
		?>
	</p>
	<?php
}

/**
 * Callback fields for the request permission notification fields options.
 *
 * @since 2.0.3
 *
 * @return void
 */
function bb_onesignal_admin_setting_callback_request_permission_fields() {
	$checked  = bb_onesignal_request_permission();
	$validate = bb_onesignal_permission_validate();

	$html = '<select name="bb-onesignal-permission-validate" ' . disabled( ! $checked, true, false ) . '>' .
			'<option value="visit" ' . selected( $validate, 'visit', false ) . '>' . esc_html__( 'visit', 'buddyboss-pro' ) . '</option>' .
			'<option value="login"' . selected( $validate, 'login', false ) . '>' . esc_html__( 'login', 'buddyboss-pro' ) . '</option>' .
			'</select>';

	?>
	<input id="bb-onesignal-request-permission" name="bb-onesignal-request-permission" type="checkbox" value="1"
		<?php checked( $checked ); ?>
	/>
	<label for="bb-onesignal-request-permission">
		<?php
			printf(
				wp_kses_post(
					/* translators: Permission validate select box. */
					__( 'Request permission to send notifications when members first %s through a new browser', 'buddyboss-pro' )
				),
				$html // phpcs:ignore
			)
		?>
	</label>
	<p class="description">
		<?php
		printf(
			wp_kses_post(
				/* translators: Notification Preferences text. */
				__( 'When enabled, a prompt will be presented by the browser, requesting permission for the site to send web push notifications. The member must click "Allow" to be subscribed. If disabled, members will need to allow permission in the %s tab of their Account Settings.', 'buddyboss-pro' )
			),
			'<strong>' . esc_html__( 'Notification Preferences', 'buddyboss-pro' ) . '</strong> '
		);
		?>
	</p>
	<?php
}

/**
 * Callback fields for the enable soft prompt fields options.
 *
 * @since 2.0.3
 *
 * @return void
 */
function bb_onesignal_admin_setting_callback_enable_soft_prompt_fields() {
	$checked = bb_onesignal_enable_soft_prompt();
	?>
	<input id="bb-onesignal-enable-soft-prompt" name="bb-onesignal-enable-soft-prompt" type="checkbox" value="1" <?php checked( $checked ); ?>/>
	<label for="bb-onesignal-enable-soft-prompt"><?php esc_html_e( 'Show a "soft prompt" before triggering the browser\'s native permission prompt', 'buddyboss-pro' ); ?></label>
	<div class="description small-text">
		<p>
			<?php
				printf(
					wp_kses_post(
						/* translators: Learn more link . */
						__( 'A "soft prompt" is a customizable prompt that is show to the member before the "hard prompt" of the native permission prompt triggered by the browser. %s', 'buddyboss-pro' )
					),
					'<a href="javascript:;" class="bb-learn-more">' . esc_html__( 'Learn More', 'buddyboss-pro' ) . '</a>'
				)
			?>
		</p>
	</div>
	<div class="description full-text bp-hide">
		<?php
		echo '<p>' . esc_html__( 'A "soft prompt" is a customizable prompt that is show to the member before the "hard prompt" of the native permission prompt triggered by the browser.', 'buddyboss-pro' ) . '</p>' .
			'<p>' . esc_html__( 'If a member denies the native permission prompt, they will need to go through a multi-step process to enable notification permissions in their browser. As such, a "soft prompt" is highly beneficial, as it allows you opportunity to persuade your members to subscribe to web push notifications before the native permission prompt is triggered.', 'buddyboss-pro' ) . '</p>' .
			'<p>' . esc_html__( 'However, the "soft prompt" does not replace the native permission prompt and does not subscribe the member\'s browser to receive web push notifications.', 'buddyboss-pro' ) . '</p>';
		?>
	</div>
	<?php
}

/**
 * Callback fields for soft prompt description text.
 *
 * @since 2.0.3
 *
 * @return void
 */
function bb_onesignal_admin_setting_callback_enable_soft_prompt_fields_message() {
	$required = bb_onesignal_enable_soft_prompt();
	$text     = bb_onesignal_soft_prompt_message_text();
	?>
	<p class="description soft_prompt_label_header"><?php esc_html_e( 'Message', 'buddyboss-pro' ); ?></p>
	<textarea maxlength="90" rows="4" cols="75" name="bb-onesignal-enable-soft-prompt-message" id="bb-onesignal-enable-soft-prompt-message" aria-label="<?php esc_html_e( 'Message', 'buddyboss-pro' ); ?>" placeholder="<?php echo esc_attr( bb_onesignal_soft_prompt_message_placeholder_text() ); ?>"><?php echo wp_kses_post( $text ); ?></textarea>
	<p class="description bp-hide">
		<span class="current"><?php echo esc_html( strlen( $text ) ); ?></span><?php esc_html_e( '/90 characters', 'buddyboss-pro' ); ?>
	</p>
	<?php
}

/**
 * Callback fields for soft prompt image field.
 *
 * @since 2.0.3
 *
 * @return void
 */
function bb_onesignal_admin_setting_callback_enable_soft_prompt_fields_image() {
	$hide_show_style           = 'bp-inline-block';
	$default_soft_prompt_image = bb_onesignal_soft_prompt_image();

	if ( ! $default_soft_prompt_image ) {
		$hide_show_style = 'bp-hide';
	}
	?>

	<div class="bb-default-custom-upload-file bbpro-upload-attachment" data-object="prompt">
		<p class="description soft_prompt_label_header"><?php esc_html_e( 'Image', 'buddyboss-pro' ); ?></p>
		<div class="bb-upload-container bbpro-attachment-upload-container">
			<img src="<?php echo esc_url( $default_soft_prompt_image ); ?>" class="bb-upload-preview prompt-0-avatar <?php echo esc_attr( $hide_show_style ); ?>" >
			<input type="hidden" name="bb-onesignal-enable-soft-prompt-image" class="bb-default-custom-avatar-field" value="<?php echo esc_url( $default_soft_prompt_image ); ?>">
		</div>
		<div class="bb-img-button-wrap">
			<a href="#TB_inline?width=800px&height=400px&inlineId=bbpro-notification-icon-editor" class="button button-large thickbox bb-attachment-user-edit" data-uploading="<?php esc_html_e( 'Uploading...', 'buddyboss-pro' ); ?>" data-upload="<?php esc_html_e( 'Upload', 'buddyboss-pro' ); ?>"><?php esc_html_e( 'Upload', 'buddyboss-pro' ); ?></a>
			<a href="#" class="delete button button-large bbpro-img-remove-button  <?php echo esc_attr( $hide_show_style ); ?>" data-removing="<?php esc_html_e( 'Removing...', 'buddyboss-pro' ); ?>" data-remove="<?php esc_html_e( 'Remove', 'buddyboss-pro' ); ?>"><?php esc_html_e( 'Remove', 'buddyboss-pro' ); ?></a>
		</div>
		<div class="bp-messages-feedback admin-notice bbpro-attachment-status" style="display: none;">
			<div class="bp-feedback">
				<p class="bbpro-attachment-upload-feedback"></p>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Callback fields for soft prompt buttons.
 *
 * @since 2.0.3
 *
 * @return void
 */
function bb_onesignal_admin_setting_callback_enable_soft_prompt_fields_buttons() {
	$required           = bb_onesignal_enable_soft_prompt();
	$allow_button_text  = bb_onesignal_soft_prompt_allow_btn_text();
	$cancel_button_text = bb_onesignal_soft_prompt_cancel_btn_text();
	?>
	<div class="button-wrap">
		<p class="description soft_prompt_label_header"><?php esc_html_e( 'Allow Button', 'buddyboss-pro' ); ?></p>
		<input maxlength="15" name="bb-onesignal-enable-soft-prompt-allow-button" id="bb-onesignal-enable-soft-prompt-allow-button" type="text" value="<?php echo esc_html( $allow_button_text ); ?>" aria-label="<?php esc_html_e( 'Allow Button', 'buddyboss-pro' ); ?>" placeholder="<?php echo esc_attr( bb_onesignal_soft_prompt_allow_btn_placeholder_text() ); ?>"/>
		<p class="description bp-hide">
			<span class="current"><?php echo esc_html( strlen( $allow_button_text ) ); ?></span><?php esc_html_e( '/15 characters', 'buddyboss-pro' ); ?>
		</p>
	</div>
	<div class="button-wrap">
		<p class="description soft_prompt_label_header"><?php esc_html_e( 'Cancel Button', 'buddyboss-pro' ); ?></p>
		<input maxlength="15" name="bb-onesignal-enable-soft-prompt-cancel-button" id="bb-onesignal-enable-soft-prompt-cancel-button" type="text" value="<?php echo esc_html( $cancel_button_text ); ?>" aria-label="<?php esc_html_e( 'Cancel Button', 'buddyboss-pro' ); ?>" placeholder="<?php echo esc_attr( bb_onesignal_soft_prompt_cancel_btn_placeholder_text() ); ?>"/>
		<p class="description bp-hide">
			<span class="current"><?php echo esc_html( strlen( $cancel_button_text ) ); ?></span><?php esc_html_e( '/15 characters', 'buddyboss-pro' ); ?>
		</p>
	</div>
	<?php
}

/**
 * Callback fields for soft prompt preview box.
 *
 * @since 2.0.3
 *
 * @return void
 */
function bb_onesignal_admin_setting_callback_enable_soft_prompt_fields_preview_box() {

	$default_icon              = "data:image/svg+xml,%3Csvg fill='none' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 40 40'%3E%3Cg clip-path='url(%23clip0)'%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M33.232 28.434a2.5 2.5 0 001.768.733 1.667 1.667 0 010 3.333H5a1.667 1.667 0 110-3.333 2.5 2.5 0 002.5-2.5v-8.104A13.262 13.262 0 0118.333 5.122V1.667a1.666 1.666 0 113.334 0v3.455A13.262 13.262 0 0132.5 18.563v8.104a2.5 2.5 0 00.732 1.767zM16.273 35h7.454a.413.413 0 01.413.37 4.167 4.167 0 11-8.28 0 .417.417 0 01.413-.37z' fill='%23BDC4CB'/%3E%3C/g%3E%3Cdefs%3E%3CclipPath id='clip0'%3E%3Cpath fill='%23fff' d='M0 0h40v40H0z'/%3E%3C/clipPath%3E%3C/defs%3E%3C/svg%3E";
	$default_soft_prompt_image = bb_onesignal_soft_prompt_image();

	if ( empty( $default_soft_prompt_image ) ) {
		$default_soft_prompt_image = $default_icon;
	}

	$action_message = esc_html( bb_onesignal_soft_prompt_message_text() );
	if ( empty( $action_message ) ) {
		$action_message = bb_onesignal_soft_prompt_message_placeholder_text();
	}

	$accept_button_text = esc_html( bb_onesignal_soft_prompt_allow_btn_text() );
	if ( empty( $accept_button_text ) ) {
		$accept_button_text = bb_onesignal_soft_prompt_allow_btn_placeholder_text();
	}

	$cancel_button_text = esc_html( bb_onesignal_soft_prompt_cancel_btn_text() );
	if ( empty( $cancel_button_text ) ) {
		$cancel_button_text = bb_onesignal_soft_prompt_cancel_btn_placeholder_text();
	}

	?>
	<p class="description soft_prompt_label_header prompt-box-preview-title"><?php esc_html_e( 'Preview', 'buddyboss-pro' ); ?></p>
	<div class="soft-prompt-box-wrapper-preview">
		<div class="soft-prompt-image">
			<img src="<?php echo esc_attr( $default_soft_prompt_image ); ?>" data-default="<?php echo esc_attr( $default_icon ); ?>" />
		</div>
		<div class="soft-prompt-text">
			<?php echo wp_kses_post( $action_message ); ?>
		</div>
		<div class="soft-prompt-btn">
			<?php
			echo '<a href="#" class="cancel-soft-prompt-button">' . wp_kses_post( $cancel_button_text ) . '</a>';

			echo '<button type="button" class="allow-soft-prompt-button button button-primary button-large">' . wp_kses_post( $accept_button_text ) . '</button>';
			?>
		</div>
	</div>
	<?php

}

/**
 * Get the browser name of given user agent.
 *
 * @since 2.0.3
 *
 * @param string $user_agent Server user agent.
 *
 * @return string
 */
function bb_get_browser_name( $user_agent ) {
	$user_agent = strtolower( $user_agent );
	$user_agent = ' ' . $user_agent;
	if ( strpos( $user_agent, 'opera' ) || strpos( $user_agent, 'opr/' ) ) {
		return 'Opera';
	} elseif ( strpos( $user_agent, 'edge' ) || strpos( $user_agent, 'edg/' ) ) {
		return 'Edge';
	} elseif ( strpos( $user_agent, 'chrome' ) ) {
		return 'Chrome';
	} elseif ( strpos( $user_agent, 'safari' ) ) {
		return 'Safari';
	} elseif ( strpos( $user_agent, 'firefox' ) ) {
		return 'Firefox';
	} elseif ( strpos( $user_agent, 'msie' ) || strpos( $user_agent, 'trident/7' ) ) {
		return 'IE';
	}

	return 'Unknown';
}

/**
 * Functions to send notification based on data provided.
 *
 * @param array $data Array of data params.
 *
 * @return void
 */
function bb_onesingnal_send_notification( $data ) {

	$rest_api_key = bb_onesignal_connected_app_rest_api_key();
	$app_id       = bb_onesignal_connected_app();

	if ( empty( $rest_api_key ) || empty( $app_id ) ) {
		return;
	}

	$data = bp_parse_args(
		$data,
		array(
			'user_id' => 0,
			'title'   => '',
			'content' => '',
			'link'    => '',
			'image'   => '',
		),
		'onesignal_create_notification'
	);

	$args = array(
		'sslverify' => false,
		'headers'   => array(
			'Authorization' => 'Basic ' . $rest_api_key,
			'Content-Type'  => 'application/json; charset=utf-8',
		),
	);

	$lang = substr( get_bloginfo( 'language' ), 0, 2 );

	$fields = array(
		'app_id'                    => $app_id,
		'headings'                  => array(
			$lang => html_entity_decode( wp_encode_emoji( $data['title'] ) ),
		),
		'contents'                  => array(
			$lang => html_entity_decode( wp_encode_emoji( stripcslashes( $data['content'] ) ) ),
		),
		'include_external_user_ids' => wp_parse_list( $data['user_id'] ),

	);

	if ( isset( $data['link'] ) && ! empty( $data['link'] ) ) {
		$fields['url'] = $data['link'];
	}

	if ( isset( $data['image'] ) && ! empty( $data['image'] ) ) {
		$fields['chrome_web_icon']  = esc_url( $data['image'] );
		$fields['chrome_web_badge'] = esc_url( $data['image'] );
		$fields['firefox_icon']     = esc_url( $data['image'] );
	}

	$args['body'] = wp_json_encode( $fields );
	bbpro_remote_post( bb_onesignal_notification_endpoint(), $args );

}

/**
 * Fetch the current browser name.
 *
 * @since 2.0.3
 *
 * @return string
 */
function bb_onesignal_get_current_browser() {
    // phpcs:ignore
	$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '';

	if ( strpos( $user_agent, 'Opera' ) || strpos( $user_agent, 'OPR/' ) ) {
		return 'Opera';
	} elseif ( strpos( $user_agent, 'Edge' ) || strpos( $user_agent, 'Edg' ) ) {
		return 'Edge';
	} elseif ( strpos( $user_agent, 'Chrome' ) ) {
		return 'Chrome';
	} elseif ( strpos( $user_agent, 'Safari' ) ) {
		return 'Safari';
	} elseif ( strpos( $user_agent, 'Firefox' ) ) {
		return 'Firefox';
	} elseif ( strpos( $user_agent, 'MSIE' ) || strpos( $user_agent, 'Trident/7' ) ) {
		return 'IE';
	}

	return '';
}
