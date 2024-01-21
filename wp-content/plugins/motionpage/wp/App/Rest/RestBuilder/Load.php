<?php

/**
 * Motion.page
 *
 * @package   motionpage
 * @author    HypeWolf OÜ <hello@hypewolf.com>
 * @copyright 2022 HypeWolf OÜ
 * @license   EULA + GPLv2
 * @link      https://motion.page
 */

declare(strict_types=1);

namespace motionpage\App\Rest\RestBuilder;

/**
 * The callback for the [LOAD] REST API endpoint
 * @since 2.1.0
 */
class Load extends AllPoints {
	public function loadPath(): \WP_REST_Response {
		global $wpdb;
		$table_prefix = $wpdb->prefix . MOTIONPAGE_NAME;

		$plugin_dbv = motionpage()->getData()['database_version'];
		$current_dbv = motionpage()->getOption(MOTIONPAGE_NAME . '_db_version', $plugin_dbv);
		$newer_or_same_as_2_1 = version_compare($current_dbv, $plugin_dbv, '>=');
		if ($newer_or_same_as_2_1) {
			$code_table = $table_prefix . '_code';
			$data_table = $table_prefix . '_data';

			$query_string = sprintf(
				'SELECT * FROM %s LEFT JOIN %s ON %s',
				$data_table,
				$code_table,
				$data_table . '.id = ' . $code_table . '.data_id'
			);
			$data_rows = $wpdb->get_results($query_string);
			if (\is_wp_error($data_rows)) {
				return new \WP_REST_Response(['error' => 'Database query failed!'], 500);
			}

			return new \WP_REST_Response($data_rows);
		}

		$scripts_table = $table_prefix . '_scripts';
		$scripts_rows = $wpdb->get_results(sprintf('SELECT * FROM %s', $scripts_table));
		if (\is_wp_error($scripts_rows)) {
			return new \WP_REST_Response(['error' => 'Database query failed!'], 500);
		}

		// Ensure that the data satisfies the Zod schema
		$modified_scripts_rows = array_map(function ($row) {
			unset($row->trigger_is_active);
			$row->data_id = $row->id;
			return $row;
		}, $scripts_rows);

		return new \WP_REST_Response($modified_scripts_rows);
	}
}
