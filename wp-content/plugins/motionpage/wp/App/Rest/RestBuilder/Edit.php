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
 * The callback for the [EDIT] REST API endpointt
 * @since 2.1.0
 */
class Edit extends AllPoints {
	public function editPath(\WP_REST_Request $request) {
		global $wpdb;
		$wpdb->hide_errors();

		$params = $request->get_json_params();
		$code_table = $wpdb->prefix . MOTIONPAGE_NAME . '_code';

		switch ($params['type']) {
			case 'toggleGlobal':
				return $this->toggleGlobal($params, $wpdb, $code_table);
			case 'toggleActive':
				return $this->toggleActive($params, $wpdb, $code_table);
			case 'updateAnimation':
				return $this->updateAnimation($params, $wpdb, $code_table);

			default:
				http_response_code(400);
				exit();
		}
	}

	private function toggleGlobal($params, $wpdb, string $code_table): \WP_REST_Response {
		$id = $params['id'];
		$is_global_state = $params['is_global'];

		$query_string = sprintf('SELECT EXISTS (SELECT 1 FROM %s WHERE id = %%d)', $code_table);
		$wpdb->get_var($wpdb->prepare($query_string, $id));

		if ($wpdb->last_error !== '') {
			return new \WP_REST_Response(['error' => $wpdb->last_error], 500);
		}

		$wpdb->update($code_table, ['is_global' => $is_global_state], ['id' => $id]);
		if ($wpdb->last_error !== '') {
			return new \WP_REST_Response(['error' => $wpdb->last_error], 500);
		}

		\do_action('motionpage/action/api/update');
		return new \WP_REST_Response([
			'message' => sprintf('Timeline %s global state toggled to %s', $id, $is_global_state)
		]);
	}

	private function toggleActive($params, $wpdb, string $code_table): \WP_REST_Response {
		$id = $params['id'];
		$is_active_state = $params['is_active'];

		$query_string = sprintf('SELECT EXISTS (SELECT 1 FROM %s WHERE id = %%d)', $code_table);
		$wpdb->get_var($wpdb->prepare($query_string, $id));

		if ($wpdb->last_error !== '') {
			return new \WP_REST_Response(['error' => $wpdb->last_error], 500);
		}

		$wpdb->update($code_table, ['is_active' => $is_active_state], ['id' => $id]);
		if ($wpdb->last_error !== '') {
			return new \WP_REST_Response(['error' => $wpdb->last_error], 500);
		}

		\do_action('motionpage/action/api/update');
		return new \WP_REST_Response([
			'message' => sprintf('Timeline %s activate state toggled to %s', $id, $is_active_state)
		]);
	}

	private function updateAnimation($params, $wpdb, string $code_table): \WP_REST_Response {
		$id = $params['id'];

		$errors = [];
		$updateDataRow = function ($id) use ($wpdb, $params, &$errors) {
			$data_table = $wpdb->prefix . MOTIONPAGE_NAME . '_data';
			$query_string = sprintf('SELECT EXISTS (SELECT 1 FROM %s WHERE id = %%d)', $data_table);
			$wpdb->get_var($wpdb->prepare($query_string, $id));

			if ($wpdb->last_error !== '') {
				$errors[] = ['error' => $wpdb->last_error];
				return;
			}

			$updated = $wpdb->update(
				$data_table,
				[
					'script_name' => $params['script_name'],
					'trigger_name' => $params['trigger_name'],
					'reload' => $params['reload']
				],
				['id' => $id]
			);

			if ($wpdb->last_error !== '') {
				$errors[] = ['error' => $wpdb->last_error];
				return;
			}

			if ($updated === false) {
				$errors[] = ['error' => 'No rows updated'];
				return;
			}

			return $id;
		};

		$id = $updateDataRow($id);

		if (!empty($errors)) {
			return new \WP_REST_Response(['errors' => $errors], 500);
		}

		$errors = [];
		$updateCodeRow = function ($id) use ($wpdb, $code_table, $params, &$errors) {
			$query_string = sprintf('SELECT EXISTS (SELECT 1 FROM %s WHERE id = %%d)', $code_table);
			$wpdb->get_var($wpdb->prepare($query_string, $id));

			if ($wpdb->last_error !== '') {
				$errors[] = ['error' => $wpdb->last_error];
				return;
			}

			$updated = $wpdb->update(
				$code_table,
				[
					'script_value' => $params['script_value'],
					'post_id' => $params['post_id'],
					'is_global' => $params['is_global'],
					'plugins' => $params['plugins'],
					'types' => $params['types'],
					'cats' => $params['cats'],
					'data_id' => $id
				],
				['id' => $id]
			);

			if ($wpdb->last_error !== '') {
				$errors[] = ['error' => $wpdb->last_error];
				return;
			}

			if ($updated === false) {
				$errors[] = ['error' => 'No rows updated'];
				return;
			}

			return $id;
		};

		$id = $updateCodeRow($id);

		if (!empty($errors)) {
			return new \WP_REST_Response(['errors' => $errors], 500);
		}

		\do_action('motionpage/action/api/update');
		return new \WP_REST_Response(['ids' => ['data_id' => $id]]);
	}
}
