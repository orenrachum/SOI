<?php
/**
 * Frames Updater file.
 *
 * @package Frames_Client
 */

namespace Frames_Client\Admin;

use Frames_Client\Helpers\Logger;

if ( ! class_exists( '\EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater.
	include FRAMES_PLUGIN_DIR . '/library/EDD/EDD_SL_Plugin_Updater.php';
}

/**
 * Frames Updater class
 */
class Plugin_Updater {
	/**
	 * This is the URL our updater / license checker pings. This should be the URL of the site with EDD installed.
	 *
	 * @var string
	 */
	private $store_url = 'https://getframes.io/';

	/**
	 * The download ID for the product in Easy Digital Downloads.
	 *
	 * @var integer
	 */
	private $store_item_id = 176;

	/**
	 * The name of the product in Easy Digital Downloads.
	 *
	 * @var string
	 */
	private $store_item_name = 'Frames (Bricks Builder)';

	/**
	 * The name of the settings page for the license input to be displayed.
	 *
	 * @var string
	 */
	private $plugin_license_page = 'frames-updater';

	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	private $license_key_option = 'frames_license_key';

	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	private $status_option = 'frames_license_status';

	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	private $beta_option = 'frames_license_beta';

	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	private $nonce_field = 'frames_license_nonce';

	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	private $nonce_value = 'frames_license_nonce';

	/**
	 * Undocumented function
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'plugin_updater' ) );
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_init', array( $this, 'register_option' ) );
			add_action( 'admin_init', array( $this, 'handle_license_activation' ) );
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		}
	}

	/**
	 * Initialize the updater. Hooked into `init` to work with the
	 * wp_version_check cron job, which allows auto-updates.
	 */
	public function plugin_updater() {
		// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
		$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
		if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
			return;
		}
		// retrieve our license key from the DB.
		$license_key = trim( get_option( $this->license_key_option ) );
		// get the plugin's info.
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		$plugin_data = get_plugin_data( FRAMES_PLUGIN_FILE );
		$version = $plugin_data['Version'];
		$author = $plugin_data['Author'];
		$beta_option = trim( get_option( $this->beta_option ) );
		$beta_value = '' !== $beta_option && true === (bool) $beta_option ? true : false;
		// setup the updater.
		$edd_updater = new \EDD_SL_Plugin_Updater(
			$this->store_url,
			FRAMES_PLUGIN_FILE,
			array(
				'version' => $version,              // current version number.
				'license' => $license_key,          // license key (used get_option above to retrieve from DB).
				'item_id' => $this->store_item_id,  // ID of the product.
				'author'  => $author,               // author of this plugin.
				'beta'    => $beta_value
			)
		);
	}

	/**
	 * Adds the plugin license page to the admin menu.
	 *
	 * @return void
	 */
	public function admin_menu() {
		add_submenu_page(
			'automatic-css', // parent slug.
			__( 'Frames License' ), // page title.
			__( 'Frames License' ), // menu title.
			'manage_options', // capability.
			$this->plugin_license_page, // page slug.
			array( $this, 'settings_page' ) // callback.
		);
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function settings_page() {
		add_settings_section(
			'frames_license',
			__( 'Plugin License' ),
			array( $this, 'settings_section' ),
			$this->plugin_license_page
		);
		add_settings_field(
			$this->license_key_option,
			'<label for="' . esc_attr( $this->license_key_option ) . '">' . __( 'License Key' ) . '</label>',
			array( $this, 'settings_fields' ),
			$this->plugin_license_page,
			'frames_license'
		);
		?>
			<div class="wrap">
				<h2><?php esc_html_e( 'Plugin License Options' ); ?></h2>
				<form method="post" action="options.php">
					<?php
					do_settings_sections( $this->plugin_license_page );
					settings_fields( 'frames_license' );
					?>
				</form>
			</div>
		<?php
	}

	/**
	 * Adds content to the settings section.
	 *
	 * @return void
	 */
	public function settings_section() {
		printf(
			'<p>Please enter your Frames license key.</p>
			<p class="frames-alert-box">Remember to also save the license key in your <strong>Remote Templates Password field</strong> in the <a href="%s">Bricks Settings</a></p>',
			esc_url( admin_url( 'admin.php?page=bricks-settings#tab-templates' ) )
		);
	}

	/**
	 * Outputs the license key settings field.
	 *
	 * @return void
	 */
	public function settings_fields() {
		$license = get_option( $this->license_key_option );
		$status  = get_option( $this->status_option );
		printf(
			'<input type="password" class="regular-text" id="%1$s" name="%1$s" value="%2$s" />',
			esc_attr( $this->license_key_option ),
			esc_attr( $license )
		);
		$button = array(
			'name'  => 'frames_edd_license_deactivate',
			'label' => __( 'Deactivate License' )
		);
		if ( 'valid' !== $status ) {
			$button = array(
				'name'  => 'frames_edd_license_activate',
				'label' => __( 'Activate License' )
			);
		}
		wp_nonce_field( $this->nonce_field, $this->nonce_value );
		?>
			<div class="frames-license__field-group" style="margin-top: 15px;">
				<input type="submit" class="button-primary" name="frames_edd_license_activate" value="Save & Activate"/>
				<input type="submit" class="button-secondary" name="frames_edd_license_deactivate" value="Delete & Deactivate"/>
			</div>
		<?php if ( ! empty( $status ) ) : ?>
			<div class="frames-license__field-group">
				<p>Activation status: <?php echo esc_html( $status ); ?></p>
			</div>
			<?php
		endif;
	}

	/**
	 * Registers the license key setting in the options table.
	 *
	 * @return void
	 */
	public function register_option() {
		register_setting( 'frames_license', $this->license_key_option, array( $this, 'sanitize_license' ) );
	}

	/**
	 * Sanitizes the license key.
	 *
	 * @param string $new The license key.
	 * @return string
	 */
	public function sanitize_license( $new ) {
		$old = get_option( $this->license_key_option );
		if ( $old && $old !== $new ) {
			delete_option( $this->status_option ); // new license has been entered, so must reactivate.
		}
		return sanitize_text_field( $new );
	}

	/**
	 * Handle plugin license activation & deactivation
	 *
	 * @return void
	 */
	public function handle_license_activation() {
		// listen for our activate button to be clicked.
		if ( ! isset( $_POST['frames_edd_license_activate'] ) && ! isset( $_POST['frames_edd_license_deactivate'] ) ) {
			return;
		}
		// run a quick security check.
		if ( ! check_admin_referer( $this->nonce_field, $this->nonce_value ) ) {
			return; // get out if we didn't click the Activate button.
		}
		// retrieve the license from the form and save it.
		$license = trim( filter_input( INPUT_POST, $this->license_key_option ) );
		$edd_action = isset( $_POST['frames_edd_license_activate'] ) ? 'activate_license' : 'deactivate_license';
		// data to send in our API request.
		$api_params = array(
			'edd_action'  => $edd_action,
			'license'     => $license,
			'item_id'     => $this->store_item_id,
			'item_name'   => rawurlencode( $this->store_item_name ), // the name of our product in EDD.
			'url'         => site_url(),
			'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production'
		);
		// Call the custom API.
		$response = wp_remote_post(
			$this->store_url,
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params
			)
		);
		if ( 'activate_license' === $edd_action ) {
			$this->activate_license( $response, $license );
		} else {
			$this->deactivate_license( $response, $license );
		}
	}

	/**
	 * Activates the license key.
	 *
	 * @param array|WP_Error $response The response or WP_Error on failure.
	 * @param string         $license The license key.
	 * @return void
	 */
	private function activate_license( $response, $license ) {
		$sl_activation = 'false';
		// make sure the response came back okay.
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.' );
			}
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			if ( false === $license_data->success ) {
				switch ( $license_data->error ) {
					case 'expired':
						$message = sprintf(
						/* translators: the license key expiration date */
							__( 'Your license key expired on %s.', 'automatic-css' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;
					case 'disabled':
					case 'revoked':
						$message = __( 'Your license key has been disabled.', 'automatic-css' );
						break;
					case 'missing':
						$message = __( 'Invalid license.', 'automatic-css' );
						break;
					case 'invalid':
					case 'site_inactive':
						$message = __( 'Your license is not active for this URL.', 'automatic-css' );
						break;
					case 'item_name_mismatch':
						/* translators: the plugin name */
						$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'automatic-css' ), $this->store_item_name );
						break;
					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.', 'automatic-css' );
						break;
					default:
						$message = __( 'An error occurred, please try again.', 'automatic-css' );
						break;
				}
			} else {
				$message = __( 'Your license key has been activated.', 'automatic-css' );
				$sl_activation = 'true';
			}
		}
		// update options.
		update_option( $this->license_key_option, $license );
		update_option( $this->status_option, $license_data->license );
		// send message and redirect.
		$redirect = add_query_arg(
			array(
				'page'          => $this->plugin_license_page,
				'sl_activation' => $sl_activation,
				'message'       => rawurlencode( $message )
			),
			admin_url( 'admin.php?page=' . $this->plugin_license_page ) // was: plugins.php.
		);
		wp_safe_redirect( $redirect );
		exit();
	}

	/**
	 * Deactivates the license key.
	 *
	 * @param array|WP_Error $response The response or WP_Error on failure.
	 * @param string         $license The license key.
	 * @return void
	 */
	private function deactivate_license( $response, $license ) {
		// make sure the response came back okay.
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.' );
			}
		} else {
			// decode the license data.
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			// $license_data->license will be either "deactivated" or "failed".
			switch ( $license_data->license ) {
				case 'failed':
					$message = __( 'Your license key was NOT deactivated.', 'automatic-css' );
					break;
				case 'deactivated':
					$message = __( 'Your license key has been deactivated.', 'automatic-css' );
					$sl_activation = 'true';
					break;
				default:
			}
		}
		delete_option( $this->license_key_option );
		delete_option( $this->status_option );
		$redirect = add_query_arg(
			array(
				'page'          => $this->plugin_license_page,
				'message'       => rawurlencode( $message )
			),
			admin_url( 'admin.php?page=' . $this->plugin_license_page ) // was: plugins.php.
		);
		wp_safe_redirect( $redirect );
		exit();
	}

	/**
	 * Checks if a license key is still valid.
	 * The updater does this for you, so this is only needed if you want
	 * to do somemthing custom.
	 *
	 * @return mixed
	 */
	public function frames_check_license() {
		$license = trim( get_option( $this->license_key_option ) );
		$api_params = array(
			'edd_action'  => 'check_license',
			'license'     => $license,
			'item_id'     => $this->store_item_id,
			'item_name'   => rawurlencode( $this->store_item_name ),
			'url'         => site_url(),
			'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production'
		);
		// Call the custom API.
		$response = wp_remote_post(
			$this->store_url,
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params
			)
		);
		if ( is_wp_error( $response ) ) {
			return false;
		}
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		if ( 'valid' === $license_data->license ) {
			echo 'valid';
			exit;
			// this license is still valid.
		} else {
			echo 'invalid';
			exit;
			// this license is no longer valid.
		}
	}

	/**
	 * This is a means of catching errors from the activation method above and displaying it to the customer
	 */
	public function admin_notices() {
		$page = filter_input( INPUT_GET, 'page' );
		$sl_activation = filter_input( INPUT_GET, 'sl_activation' );
		$message = filter_input( INPUT_GET, 'message' );
		if ( $this->plugin_license_page === $page && isset( $sl_activation ) && ! empty( $message ) ) {
			$message = urldecode( $message );
			switch ( $sl_activation ) {
				case 'false':
					?>
				<div class="notice notice-error">
					<p><?php echo wp_kses_post( $message ); ?></p>
				</div>
					<?php
					break;
				case 'true':
					?>
				<div class="notice notice-success">
					<p><?php echo wp_kses_post( $message ); ?></p>
				</div>
					<?php
					break;
				default:
					// Developers can put a custom success message here for when activation is successful if they way.
					break;

			}
		}
	}
}
