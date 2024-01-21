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
 * The callback for the [REBUILD] REST API endpoint
 * @since 2.1.0
 */
class Rebuild extends AllPoints {
	/**
	 * - rebuilds the database via modifyDatabase()
	 * - multisite support
	 * - /wp-json/motionpage/v1/rebuild
	 * @return object{ids:object{code_id:int,data_id:int}[]}|object{errors:object{errors:object{error:string,id:int,type:string,data_id?:int}[]}}
	 */
	public function rebuildPath(): \WP_REST_Response {
		$database_res = [];
		$network_wide = \is_multisite();

		if ($network_wide) {
			$errors = [];

			foreach (\get_sites() as $site) {
				\switch_to_blog($site->blog_id);
				$database_res = $this->modifyDatabase();
				if (isset($database_res['errors'])) {
					$errors[] = ['blog_id' => $site->blog_id, 'errors' => $database_res['errors']];
				}

				\restore_current_blog();
			}

			if (!empty($errors)) {
				return new \WP_REST_Response(['errors' => $errors]);
			}

			return new \WP_REST_Response(['ids' => $database_res['ids']]);
		}

		$database_res = $this->modifyDatabase();
		if (isset($database_res['errors'])) {
			return new \WP_REST_Response(['errors' => $database_res['errors']]);
		}

		return new \WP_REST_Response(['ids' => $database_res['ids']]);
	}

	/**
	 * ### Update Database Tables and Rows to New Version
	 * @return object{ids:object{code_id:int,data_id:int}[]}|object{errors:object{error:string,id:int,type:string,data_id?:int}[]}
	 * @since   1.6.0
	 * @version 2.0
	 */
	private function modifyDatabase(): array {
		global $wpdb;
		$scripts_table = $wpdb->prefix . MOTIONPAGE_NAME . '_scripts';
		$query_string = sprintf('SELECT * FROM %s', $scripts_table); // TODO : if non existing
		$timelines = $wpdb->get_results($query_string);
		if (\is_wp_error($timelines)) {
			http_response_code(500);
			exit();
		}

		motionpage()->createDatabase(true);

		$ids = [];
		$errors = [];

		foreach ($timelines as $timeline) {
			$fill_response = $this->fillRows((array) $timeline);
			if (isset($fill_response['error'])) {
				$errors[] = $fill_response;
			} else {
				$ids[] = $fill_response;
			}
		}

		if (empty($errors)) {
			motionpage()->updateOption(MOTIONPAGE_NAME . '_db_version', $this->plugin->databaseVersion());
			return ['ids' => $ids];
		}

		$tables_suffix = ['_code', '_data'];
		foreach ($tables_suffix as $table_suffix) {
			$qss = sprintf('DROP TABLE IF EXISTS %s', $wpdb->prefix . MOTIONPAGE_NAME . $table_suffix);
			$wpdb->query($qss);
		}

		return ['errors' => $errors];
	}
}
