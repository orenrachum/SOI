<?php
/**
 * Automatic.css Divider UI file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\Admin\UI_Modules;

use Automatic_CSS\Plugin;
use Automatic_CSS\Helpers\Logger;

/**
 * Divider UI class.
 */
class Divider {

	/**
	 * Render this input.
	 *
	 * @param string $divider_id The divider ID.
	 * @param array  $divider_options The divider options.
	 * @return void
	 */
	public static function render( $divider_id, $divider_options ) {
		$condition_string = '';
		if ( ! empty( $divider_options['condition'] ) && is_array( $divider_options['condition'] ) ) {
			// Check if the variable is hidden based on its condition.
			$condition = $divider_options['condition'];
			if ( ! empty( $condition['type'] ) && 'show_only_if' === $condition['type'] ) {
				$condition_field = ! empty( $condition['field'] ) ? $condition['field'] : '';
				$condition_value = isset( $condition['value'] ) ? $condition['value'] : null;
				if ( '' !== $condition_field && null !== $condition_value ) {
					$condition_string = sprintf( 'data-condition-field="%s" data-condition-value="%s"', esc_attr( $condition_field ), esc_attr( $condition_value ) );
				}
			}
		}
		?>
		<div class="acss-divider" id="acss-divider-<?php echo esc_attr( $divider_id ); ?>"<?php echo $condition_string; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php if ( ! empty( $divider_options['content'] ) ) : ?>
			<?php echo wp_kses_post( $divider_options['content'] ); ?>
		<?php endif; ?>
		</div> <!-- .acss-divider -->
		<?php
	}

	/**
	 * Check if the divider should be rendered.
	 *
	 * @param array $divider_options Divider options.
	 * @param array $values Values of the settings.
	 * @return boolean
	 */
	private static function should_render( $divider_options, $values ) {
		if ( ! empty( $divider_options['condition'] ) && is_array( $divider_options['condition'] ) ) {
			// Check if the variable is hidden based on its condition.
			$condition = $divider_options['condition'];
			if ( ! empty( $condition['type'] ) && 'show_only_if' === $condition['type'] ) {
				$condition_field = ! empty( $condition['field'] ) ? $condition['field'] : '';
				$condition_value = isset( $condition['value'] ) ? $condition['value'] : null;
				if ( '' !== $condition_field && null !== $condition_value ) {
					$actual_value = isset( $values[ $condition_field ] ) ? $values[ $condition_field ] : null;
					if ( null !== $actual_value && $actual_value === $condition_value ) {
						return true;
					}
				}
			}
		}
		return false;
	}
}
