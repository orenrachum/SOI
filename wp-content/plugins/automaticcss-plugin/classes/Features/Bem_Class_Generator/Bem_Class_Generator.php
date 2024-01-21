<?php
/**
 * Automatic.css Contextual Menus class file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\Features\Bem_Class_Generator;

use Automatic_CSS\Features\Base;

/**
 * Builder Contextual Menus class.
 */
class Bem_Class_Generator extends Base {

	/**
	 * Initialize the feature.
	 */
	public function __construct() {
		add_action( 'acss/oxygen/in_builder_context', array( $this, 'enqueue_scripts' ) );
		add_action( 'acss/bricks/in_builder_context', array( $this, 'enqueue_scripts' ) );
		add_filter( 'script_loader_tag', array( $this, 'add_type_attribute' ), 10, 3 );
	}

	/**
	 * Enqueue scripts for the contextual menu feature.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		$path     = '/Bem_Class_Generator/js';
		$filename = 'bundle.js';
		wp_enqueue_script(
			'bem-class-generator',
			ACSS_FEATURES_URL . "{$path}/{$filename}",
			array(),
			filemtime( ACSS_FEATURES_DIR . "{$path}/{$filename}" )
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
		if ( 'bem-class-generator' === $handle ) {
			$tag = '<script type="module" src="' . esc_url( $src ) . '"></script>'; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		}
		// change the script tag by adding type="module" and return it.
		return $tag;
	}
}
