<?php
/**
 * Admin UI for Blueprint exporting.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPBE_Admin_Page {
	const MENU_SLUG = 'wp-blueprint-exporter';
	const ACTION    = 'wpbe_download_blueprint';

	/**
	 * Register hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
		add_action( 'admin_post_' . self::ACTION, array( __CLASS__, 'download_blueprint' ) );
	}

	/**
	 * Add Tools screen.
	 */
	public static function register_menu() {
		add_management_page(
			__( 'Export Blueprint', 'wp-blueprint-exporter' ),
			__( 'Export Blueprint', 'wp-blueprint-exporter' ),
			'manage_options',
			self::MENU_SLUG,
			array( __CLASS__, 'render_page' )
		);
	}

	/**
	 * Render admin page.
	 */
	public static function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to export Blueprints.', 'wp-blueprint-exporter' ) );
		}

		$settings  = self::get_settings_from_request();
		$exporter  = new WPBE_Exporter();
		$blueprint = $exporter->build_blueprint( $settings );
		$json      = $exporter->to_json( $blueprint );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Export Blueprint', 'wp-blueprint-exporter' ); ?></h1>

			<p>
				<?php esc_html_e( 'Generate a WordPress Playground Blueprint from this site. This first version exports a recipe, not a full backup.', 'wp-blueprint-exporter' ); ?>
			</p>

			<form method="get" action="">
				<input type="hidden" name="page" value="<?php echo esc_attr( self::MENU_SLUG ); ?>" />

				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Include', 'wp-blueprint-exporter' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="include_plugins" value="1" <?php checked( $settings['include_plugins'] ); ?> />
								<?php esc_html_e( 'Active plugins', 'wp-blueprint-exporter' ); ?>
							</label>
							<br />
							<label>
								<input type="checkbox" name="include_theme" value="1" <?php checked( $settings['include_theme'] ); ?> />
								<?php esc_html_e( 'Active theme', 'wp-blueprint-exporter' ); ?>
							</label>
							<br />
							<label>
								<input type="checkbox" name="include_site_options" value="1" <?php checked( $settings['include_site_options'] ); ?> />
								<?php esc_html_e( 'Site title, tagline, timezone, date/time, and permalink options', 'wp-blueprint-exporter' ); ?>
							</label>
							<br />
							<label>
								<input type="checkbox" name="include_login" value="1" <?php checked( $settings['include_login'] ); ?> />
								<?php esc_html_e( 'Admin login step', 'wp-blueprint-exporter' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="wpbe_php_version"><?php esc_html_e( 'PHP version', 'wp-blueprint-exporter' ); ?></label>
						</th>
						<td>
							<input id="wpbe_php_version" class="regular-text" type="text" name="php_version" value="<?php echo esc_attr( $settings['php_version'] ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="wpbe_wp_version"><?php esc_html_e( 'WordPress version', 'wp-blueprint-exporter' ); ?></label>
						</th>
						<td>
							<input id="wpbe_wp_version" class="regular-text" type="text" name="wp_version" value="<?php echo esc_attr( $settings['wp_version'] ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="wpbe_landing_page"><?php esc_html_e( 'Landing page', 'wp-blueprint-exporter' ); ?></label>
						</th>
						<td>
							<input id="wpbe_landing_page" class="regular-text" type="text" name="landing_page" value="<?php echo esc_attr( $settings['landing_page'] ); ?>" />
						</td>
					</tr>
				</table>

				<?php submit_button( __( 'Update Preview', 'wp-blueprint-exporter' ), 'secondary', 'submit', false ); ?>
			</form>

			<h2><?php esc_html_e( 'Preview', 'wp-blueprint-exporter' ); ?></h2>
			<textarea class="large-text code" rows="22" readonly><?php echo esc_textarea( $json ); ?></textarea>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top: 1em;">
				<input type="hidden" name="action" value="<?php echo esc_attr( self::ACTION ); ?>" />
				<?php wp_nonce_field( self::ACTION ); ?>
				<?php foreach ( $settings as $key => $value ) : ?>
					<input type="hidden" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( (string) $value ); ?>" />
				<?php endforeach; ?>
				<?php submit_button( __( 'Download blueprint.json', 'wp-blueprint-exporter' ), 'primary', 'submit', false ); ?>
			</form>

			<p>
				<?php esc_html_e( 'Custom/private plugins, custom themes, uploads, posts, pages, menus, and database content are not exported yet.', 'wp-blueprint-exporter' ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Stream a Blueprint JSON download.
	 */
	public static function download_blueprint() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to export Blueprints.', 'wp-blueprint-exporter' ) );
		}

		check_admin_referer( self::ACTION );

		$settings = self::get_settings_from_request();
		$exporter = new WPBE_Exporter();
		$json     = $exporter->to_json( $exporter->build_blueprint( $settings ) );

		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="blueprint.json"' );
		header( 'Content-Length: ' . strlen( $json ) );

		echo $json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit;
	}

	/**
	 * Read export settings from request.
	 *
	 * @return array
	 */
	private static function get_settings_from_request() {
		$has_filters = isset( $_REQUEST['page'] ) || isset( $_REQUEST['action'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		return array(
			'include_plugins'      => $has_filters ? ! empty( $_REQUEST['include_plugins'] ) : true, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			'include_theme'        => $has_filters ? ! empty( $_REQUEST['include_theme'] ) : true, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			'include_site_options' => $has_filters ? ! empty( $_REQUEST['include_site_options'] ) : true, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			'include_login'        => $has_filters ? ! empty( $_REQUEST['include_login'] ) : true, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			'php_version'          => isset( $_REQUEST['php_version'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['php_version'] ) ) : '8.3', // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			'wp_version'           => isset( $_REQUEST['wp_version'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wp_version'] ) ) : 'latest', // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			'landing_page'         => isset( $_REQUEST['landing_page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['landing_page'] ) ) : '/wp-admin/', // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		);
	}
}
