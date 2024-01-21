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

namespace motionpage\Common;

use motionpage\Common\Abstracts\Base;

/**
 * Main function class for external uses
 *
 * @see motionpage()
 * @package motionpage\Common
 */
class Functions extends Base {
	/**
	 * Get plugin data by using motionpage()->getData()
	 * @since 2.0.0
	 */
	public function getData(): array {
		return $this->plugin->data();
	}

	/**
	 * Determine if is development by using motionpage()->isDev()
	 * @since 2.0.0
	 */
	public function isDev(): bool {
		if (!function_exists('is_readable')) {
			return false;
		}

		$env = MOTIONPAGE_DIR_PATH . '.env';

		if (!is_readable($env)) {
			return false;
		}

		$env_content = file_get_contents($env);
		preg_match("/APP_ENV='(.*?)'/", $env_content, $matches);
		return !empty($matches) && ($matches[1] ?? 'live') === 'development';

		//$client = new \WP_Http();
		//$enabled = $client->head(MOTIONPAGE_DEV_URL, [
		//  'timeout' => 0.25,
		//  'sslverify' => false,
		//  'reject_unsafe_urls' => false,
		//]);

		//return \is_wp_error($enabled) ? false : !empty($enabled);
	}

	/**
	 * Create DB tables on activation for admin builder
	 * motionpage()->createDatabase()
	 * @since   1.0.0
	 * @version 1.1
	 */
	public function createDatabase($preventCreation = false): void {
		global $wpdb;

		$table_name_code = $wpdb->prefix . MOTIONPAGE_NAME . '_code';
		$table_name_data = $wpdb->prefix . MOTIONPAGE_NAME . '_data';
		$charset_collate = $wpdb->get_charset_collate();

		$createCodeTable = fn() => "CREATE TABLE IF NOT EXISTS {$table_name_code} (
				id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				script_value longtext NOT NULL,
				post_id bigint(20) NULL,
				is_global tinyint(1) NOT NULL,
				is_active tinyint(1) NOT NULL,
				plugins varchar(191) NULL,
				types varchar(191) NULL,
				cats varchar(191) NULL,
				data_id bigint(20) UNSIGNED NOT NULL,
				PRIMARY KEY (id)
			) {$charset_collate};";

		$createDataTable = fn() => "CREATE TABLE IF NOT EXISTS {$table_name_data} (
				id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				script_name varchar(191) NOT NULL,
				trigger_name varchar(191) NOT NULL,
				reload longtext NOT NULL,
				PRIMARY KEY (id)
			) {$charset_collate};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		\dbDelta($createCodeTable());
		\dbDelta($createDataTable());

		if (!$preventCreation) {
			$this->addOption(MOTIONPAGE_NAME . '_db_version', $this->getData()['database_version']);
		}
	}

	/**
	 * Create settings during activation or upgrade on mutli-site
	 * motionpage()->createSettings()
	 * @since 2.0.0
	 */
	public function createSettings($main = []): void {
		if (!empty($main)) {
			$this->addOption(MOTIONPAGE_NAME . '_main', $main);
			return;
		}

		$main_settings = motionpage()->getMainSettings();
		if (!empty($main_settings)) {
			$this->addOption(MOTIONPAGE_NAME . '_main', $main_settings);
			return;
		}

		$config = [
			'license_key' => '',
			'license_status' => 'invalid',
			'breakpoints' => [
				'laptops' => [
					'unit' => 'max-width',
					'value' => '992'
				],
				'tablets' => [
					'unit' => 'max-width',
					'value' => '768'
				],
				'phones' => [
					'unit' => 'max-width',
					'value' => '576'
				]
			],
			'cdn' => 0,
			'language' => 'en',
			'advanced' => [
				'tooltips' => 1,
				'overflow' => 1,
				'disabledTypes' => [],
				'layout' => 0,
				'hashAnchorLinks' => 0,
				'dssa' => 0,
				'debugMode' => 0
			],
			'system' => [
				'last' => MOTIONPAGE_VERSION,
				'current' => MOTIONPAGE_VERSION,
				'permission' => 'manage_options',
				'client' => 0,
				'theme' => 'dark',
				'beta' => 0,
				'editLink' => 1,
				'wipeOnUninstall' => 0,
				'polylang' => 0,
				'wpml' => 0
			],
			'scrollSmoother' => [
				'isOpen' => 0,
				'code' => '',
				'wrapper' => '',
				'content' => '',
				'ease' => 'Expo',
				'smooth' => 0.8,
				'smoothTouch' => 0,
				'effects' => '',
				'normalizeScroll' => 0,
				'ignoreMobileResize' => 0,
				'anchorFix' => 1,
				'hashFix' => 0,
				'fixedSticky' => 0,
				'speed' => 1
			]
		];

		$this->addOption(MOTIONPAGE_NAME . '_main', $config);
	}

	/**
	 * Return all results from wpdb table [motionpage_scripts]
	 * @since   1.0.0
	 */
	public function getAllScripts($POST_ID): array {
		global $wpdb;

		$plugin_dbv = $this->getData()['database_version'];
		$current_dbv = motionpage()->getOption(MOTIONPAGE_NAME . '_db_version', $plugin_dbv);

		if (version_compare($current_dbv, $plugin_dbv, '>=')) {
			$table_name = $wpdb->prefix . MOTIONPAGE_NAME . '_code';
		} else {
			$table_name = $wpdb->prefix . MOTIONPAGE_NAME . '_scripts';
		}

		$bypass = \apply_filters('motionpage/scripts/bypass', [], $POST_ID);

		if (
			is_array($bypass) &&
			!empty($bypass) &&
			count(array_filter($bypass, 'is_numeric')) === count($bypass)
		) {
			$skipIDs = implode(',', $bypass);
			$sql = sprintf('SELECT * FROM %s WHERE id NOT IN (%s)', $table_name, $skipIDs);
			return $wpdb->get_results($sql);
		}

		$sql = sprintf('SELECT * FROM %s', $table_name);
		return $wpdb->get_results($sql);
	}

	/**
	 * Return correct POST ID
	 * @since   1.5.0
	 */
	public function getPostID(): int {
		$POST_ID = \get_the_ID() ?: 0;

		if (function_exists('is_shop') && \is_shop()) {
			$POST_ID = (int) motionpage()->getOption('woocommerce_shop_page_id');
		}

		return $POST_ID;
	}

	/**
	 * Prevent Scripts from loading at all
	 */
	public function preventLoad(int $POST_ID): bool {
		$prevent_load = \apply_filters('motionpage/utils/stopper', false, $POST_ID);

		if (
			!isset($_GET['mp']) &&
			isset($_COOKIE['mp-block']) &&
			htmlspecialchars($_COOKIE['mp-block']) === 'true'
		) {
			$prevent_load = true;
		}

		if (($_GET['mp'] ?? '') === 'debug') {
			$prevent_load = true;
		}

		return $prevent_load;
	}

	/**
	 * Determine if timeline should be loaded or not
	 * @since   1.5.0
	 */
	public function getIsLive(object $timeline, int $POST_ID): bool {
		$same_post_id = (int) $timeline->post_id === $POST_ID;

		if (isset($_GET['mp']) && !empty($_GET['mp'])) {
			if ($_GET['mp'] === 'preview' && $same_post_id) {
				if (!\has_filter('motionpage/utils/bypassReduced')) {
					\add_filter('motionpage/utils/bypassReduced', '__return_true');
				}

				return true;
			}

			$limiter = strpos($_GET['mp'], '-') !== false ? '-' : ',';
			$split_by_limiter = explode($limiter, $_GET['mp']);

			if (in_array($timeline->id, $split_by_limiter)) {
				if (!\has_filter('motionpage/utils/bypassReduced')) {
					\add_filter('motionpage/utils/bypassReduced', '__return_true');
				}

				return true;
			}
		}

		if (!$timeline->is_active) {
			return false;
		}

		$is_global = filter_var($timeline->is_global, FILTER_VALIDATE_BOOLEAN);
		//Categories
		$categories = explode(',', $timeline->cats ?? '');
		$is_categories = false;
		foreach ($categories as $category) {
			if (empty($category)) {
				continue;
			}

			if (@preg_match($category, $_SERVER['REQUEST_URI'])) {
				$is_categories = true;
				break;
			}
		}

		// Post Types
		$db_types_array = explode(',', $timeline->types ?? '');
		$is_oxy_tmpl = !empty(array_intersect($this->getOxygenTemplates($POST_ID), $db_types_array));
		$is_wp_tmpl = \get_post_type($POST_ID) && in_array(\get_post_type($POST_ID), $db_types_array);
		// Other
		$is_404 = \is_404() && in_array('$404', $db_types_array);
		$is_search = \is_search() && in_array('$search', $db_types_array);

		// return true if timeline should be live on the frontend
		return $same_post_id ||
			$is_global ||
			$is_categories ||
			$is_oxy_tmpl ||
			$is_wp_tmpl ||
			$is_404 ||
			$is_search;
	}

	/**
	 * Return all post types for Oxygen Templates
	 * @since   1.5.0
	 */
	public function getOxygenTemplates(int $POST_ID): array {
		$inner_oxy_templates = [];
		if (class_exists('OxygenElement')) {
			$ct_other_template = \get_post_meta($POST_ID, 'ct_other_template', true);
			if (!empty($ct_other_template) && $ct_other_template > 0) {
				$inner_oxy_templates[] = (string) $ct_other_template;
				$parent_template = \get_post_meta($ct_other_template, 'ct_parent_template', true);

				if ($parent_template) {
					$inner_oxy_templates[] = (string) $parent_template;
				}
			} elseif (function_exists('ct_get_posts_template')) {
				$temp_id = \ct_get_posts_template($POST_ID)->ID ?? false;
				if ($temp_id) {
					$inner_oxy_templates[] = (string) $temp_id;
					$parent_template = \get_post_meta($temp_id, 'ct_parent_template', true);
					if ($parent_template) {
						$inner_oxy_templates[] = (string) $parent_template;
					}
				}
			}
		}

		return $inner_oxy_templates;
	}

	/**
	 * Check if plugins is active
	 * @since 2.0.0
	 */
	public static function isPluginActive($plugin): bool {
		if (!function_exists('is_plugin_active_for_network')) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}

		$active_plugins = motionpage()->getOption('active_plugins', []);
		return in_array($plugin, (array) $active_plugins) || \is_plugin_active_for_network($plugin);
	}

	/**
	 * Delete all hidden files from the uploads/sequence/*uid* folder + folder itself
	 * calla with motionpage()->removeFolderFiles()
	 * @since 2.0.0
	 */
	// TODO : ERRORS
	public function removeFolderFiles($folder): void {
		$sequence_folder = $this->getData()['sequence_folder'];
		$cud = \wp_upload_dir()['basedir'] . '/' . $sequence_folder . '/' . $folder;

		$iter = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($cud, \RecursiveDirectoryIterator::SKIP_DOTS),
			\RecursiveIteratorIterator::SELF_FIRST,
			\RecursiveIteratorIterator::CATCH_GET_CHILD
		);

		foreach ($iter as $fileinfo) {
			if ($fileinfo->isFile()) {
				$image = $fileinfo->getPathname();
				unlink($image);
			}
		}

		rmdir($cud);
	}

	/**
	 * Switch default upload directory to the plugin's upload directory
	 * motionpage()->modifyUploadDir()
	 * @since  1.6.0
	 */
	public function modifyUploadDir(array $uploads, string $mydir): array {
		$uploads['subdir'] = $mydir;
		$uploads['path'] = $uploads['basedir'] . $mydir;
		$uploads['url'] = $uploads['baseurl'] . $mydir;
		return $uploads;
	}

	/**
	 * Handle folder creation - not needed - unused
	 * motionpage()->createDir()
	 * @since  1.6.0
	 */
	public function createDir($name): void {
		if (!is_dir($name)) {
			$chmod_dir = 0755 & ~umask();
			if (defined('FS_CHMOD_DIR')) {
				$chmod_dir = FS_CHMOD_DIR;
			}

			mkdir($name, $chmod_dir);
		}
	}

	public function getPostTypes(): array {
		$disable_types = $this->getData()['disabled_types'];
		return array_filter(
			\get_post_types(['public' => true]),
			function ($t) use (&$disable_types): bool {
				return !in_array(
					$t,
					array_merge(
						[
							'attachment',
							'pp_email_submission',
							'pp_video_block',
							'rank_math_schema',
							'piotnetgrid',
							'piotnetgrid-archive',
							'piotnetgrid-card',
							'piotnetgrid-facet',
							'piotnetgrid-template',
							'cartflows_step',
							'jet-engine',
							'ct_template',
							'oxy_user_library'
						],
						$disable_types
					)
				);
			},
			ARRAY_FILTER_USE_KEY
		);
	}

	public function canUseCoep() {
		$browsers = ['Opera', 'Edg', 'Chrome', 'Safari', 'Firefox', 'MSIE', 'Trident'];
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$ub = '';

		foreach ($browsers as $browser) {
			if (strpos($agent, $browser) !== false) {
				$ub = $browser;
				break;
			}
		}

		switch ($ub) {
			case 'MSIE':
			case 'Trident':
			case 'Safari':
			case 'Firefox':
				return false;

			default:
				return true;
		}
	}

	//public function scheduleEddCron() {
	//	if (!\wp_next_scheduled('motionpage/cron/edd') && \is_main_site()) {
	//		\wp_schedule_event(time(), 'daily', 'motionpage/cron/edd');
	//	}
	//}

	public function flushRewriteRules($hard = true): void {
		global $wp_rewrite;
		$wp_rewrite->init();
		$wp_rewrite->flush_rules($hard);
	}

	public function addOption(string $name, $data) {
		if (\is_multisite()) {
			return \add_blog_option(\get_current_blog_id(), $name, $data);
		}

		return \add_option($name, $data, '', 'no');
	}

	public function updateOption(string $name, $data) {
		if (\is_multisite()) {
			return \update_blog_option(\get_current_blog_id(), $name, $data);
		}

		return \update_option($name, $data, 'no');
	}

	public function deleteOption(string $name) {
		if (\is_multisite()) {
			return \delete_blog_option(\get_current_blog_id(), $name);
		}

		return \delete_option($name);
	}

	public function getOption(string $option, $default = null) {
		if (\is_multisite()) {
			return \get_blog_option(\get_current_blog_id(), $option, $default);
		}

		return \get_option($option, $default);
	}

	public function getMainSettings() {
		return $this->getOption(MOTIONPAGE_NAME . '_main', []);
	}

	public function getClientModeSettings() {
		return $this->getOption(MOTIONPAGE_NAME . '_client', []);
	}

	/**
	 * Convert some objects to arrays when injecting data into the update API
	 * @since 2.0.0
	 */
	public function convertObjectToArray(\stdClass $data): array {
		// prettier-ignore
		if (!is_array($data) && !is_object($data)) return [];

		$new_data = [];
		foreach ($data as $key => $value) {
			$new_data[$key] = is_object($value) ? $this->convertObjectToArray($value) : $value;
		}

		return $new_data;
	}
}
