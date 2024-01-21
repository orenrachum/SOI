<?php
/**
 * Automatic.css Dashboard file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\UI\Dashboard;

use Automatic_CSS\Model\Database_Settings;

/**
 * Dashboard class.
 */
class Dashboard {

	/**
	 * Initialize the feature.
	 */
	public function __construct() {
		$this->enqueue_in_builders();
		add_action( 'init', array( $this, 'enqueue_in_frontend' ) );
		add_filter( 'script_loader_tag', array( $this, 'add_type_attribute' ), 10, 3 );
	}

	/**
	 * Enqueue dashboard in builder context.
	 *
	 * @return void
	 */
	public function enqueue_in_builders() {
		add_action( 'acss/bricks/in_builder_context', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue dashboard in frontend context.
	 *
	 * @return void
	 */
	public function enqueue_in_frontend() {
		if ( current_user_can( Database_Settings::CAPABILITY ) ) {
			add_action( 'acss/bricks/in_frontend_context', array( $this, 'enqueue_scripts' ) );
		}
	}

	/**
	 * Enqueue scripts for the contextual menu feature.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		$path     = '/UI/Dashboard/js';
		$filename = 'bundle.js';
		$file_url = 'development' === wp_get_environment_type() ? 'http://localhost:5173/features/Dashboard/main.js' : ACSS_CLASSES_URL . "{$path}/{$filename}";
		wp_enqueue_script(
			'dashboard',
			$file_url,
			array(),
			filemtime( ACSS_CLASSES_DIR . "{$path}/{$filename}" )
		);
	}

	/**
	 * Adds 'type="module"' to the script tag
	 *
	 * @param string $tag The original script tag.
	 * @param string $handle The script handle.
	 * @param string $src The script source.
	 * @return string
	 */
	public static function add_type_attribute( $tag, $handle, $src ) {
		// if not correct script, do nothing and return original $tag.
		if ( 'dashboard' === $handle ) {
			$tag = '<script type="module" crossorigin src="' . esc_url( $src ) . '"></script>'; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		}
		// change the script tag by adding type="module" and return it.
		return $tag;
	}
}
