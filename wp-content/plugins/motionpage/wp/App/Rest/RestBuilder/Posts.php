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
 * The callback for the [POSTS] REST API endpoint
 * @since 2.1.0
 */
class Posts extends AllPoints {
	public function postsPath(\WP_REST_Request $request): \WP_REST_Response {
		if (!$request['p']) {
			http_response_code(400);
			exit();
		}

		$decode_posts = base64_decode($request['p']);
		$posts_to_array = explode(',', $decode_posts);

		$args = [
			'numberposts' => -1,
			'posts_per_page' => -1,
			'post_type' => $posts_to_array,
			'post_status' => 'any',
			'fields' => 'ids',
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'cache_results' => false,
			'no_found_rows' => true
		];

		//if (in_array('ct_template', $posts_to_array)) {
		//  $meta_query_reusable = [
		//      'meta_query' => [
		//          [
		//              'key' => 'ct_template_type',
		//              'value' => 'reusable_part',
		//              'compare' => '==',
		//          ],
		//      ],
		//  ];
		//  $args[] = $meta_query_reusable;
		//}

		$is_wmpl = defined('ICL_LANGUAGE_CODE');

		if ($is_wmpl) {
			\do_action('wpml_switch_language', 'all');
		}

		$posts_ids = \get_posts($args);

		if ($is_wmpl) {
			\do_action('wpml_switch_language', ICL_LANGUAGE_CODE);
		}

		if ((is_countable($posts_ids) ? count($posts_ids) : 0) === 0) {
			return new \WP_REST_Response([]);
		}

		$limit_memory_low = (int) rtrim(ini_get('memory_limit')) <= 512;
		if ((is_countable($posts_ids) ? count($posts_ids) : 0) > 800 && $limit_memory_low) {
			@ini_set('memory_limit', '1024M');
		}

		global $wpdb;
		$posts_table = $wpdb->prefix . 'posts';
		$a2s = implode(',', $posts_ids);
		$query_string = sprintf('SELECT ID, post_title, post_type FROM %s WHERE id IN (%s)', $posts_table, $a2s);
		$result = $wpdb->get_results($query_string, ARRAY_A);

		if (\is_wp_error($result) || !is_array($result)) {
			http_response_code(500);
			exit();
		}

		$posts = array_map(function ($a): array {
			$id = (int) $a['ID'];
			return [
				'id' => $id,
				'title' => html_entity_decode($a['post_title']),
				'link' => \get_the_permalink($id), // Expensive function
				'type' => $a['post_type']
			];
		}, $result);

		return new \WP_REST_Response($posts);
	}
}
