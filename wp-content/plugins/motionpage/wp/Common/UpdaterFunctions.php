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
 * Updater function class for external uses
 * @see motionpageUpdater()
 * @package motionpage\Common
 */
class UpdaterFunctions extends Base {
	private function failedStoreHash(): string {
		return 'motionpage_sl_failed_http_' . hash('crc32c', $this->plugin->eddStore());
	}

	private function licenseHash(): string {
		$s = MOTIONPAGE_NAME . $this->plugin->license() . $this->plugin->eddBeta();
		return hash('crc32c', serialize($s));
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

	/**
	 * Determines if a request has recently failed.
	 * @since 2.0.0
	 * @version EDD/1.9.1
	 */
	private function requestRecentlyFailed(): bool {
		$failed_request_details = $this->getOption($this->failedStoreHash());

		// Request has never failed.
		if (empty($failed_request_details) || !is_numeric($failed_request_details)) {
			return false;
		}

		// Request previously failed, but the timeout has expired. Try again.
		if (time() > $failed_request_details) {
			$this->deleteOption($this->failedStoreHash());
			return false;
		}

		return true;
	}

	/**
	 * Gets the current version information from the remote site.
	 * @since 2.0.0
	 * @version EDD/1.9.1
	 * @return array|false
	 */
	private function getVersionFromRemote() {
		$api_params = [
			'edd_action' => 'get_version',
			'license' => empty($this->plugin->license()) ? '' : $this->plugin->license(),
			'item_id' => $this->plugin->eddID() ?? false,
			'version' => $this->plugin->version() ?? false,
			'slug' => $this->plugin->eddSlug(),
			'url' => \home_url(),
			'beta' => $this->plugin->eddBeta(),
			'php_version' => phpversion(),
			'wp_version' => \get_bloginfo('version')
		];

		$request = \wp_remote_post($this->plugin->eddStore(), [
			'timeout' => 15,
			'sslverify' => true,
			'body' => $api_params
		]);

		/**
		 * Logs a failed HTTP request for this API URL.
		 * We set a timestamp for 1 hour from now. This prevents future API requests from being
		 * made to this domain for 1 hour. Once the timestamp is in the past, API requests
		 * will be allowed again. This way if the site is down for some reason we don't bombard
		 * it with failed API requests.
		 *
		 * @see EDD_SL_Plugin_Updater::request_recently_failed
		 * @since 2.0.0
		 * @version EDD/1.9.1
		 */
		if (\is_wp_error($request) || \wp_remote_retrieve_response_code($request) !== 200) {
			$this->updateOption($this->failedStoreHash(), strtotime('+1 hour'));
			return false;
		}

		$request = json_decode(\wp_remote_retrieve_body($request), null, 512, JSON_THROW_ON_ERROR);

		if ($request && (property_exists($request, 'sections') && $request->sections !== null)) {
			$request->sections = \maybe_unserialize($request->sections);
		} else {
			$request = false;
		}

		if ($request && (property_exists($request, 'banners') && $request->banners !== null)) {
			$request->banners = \maybe_unserialize($request->banners);
		}

		if ($request && (property_exists($request, 'icons') && $request->icons !== null)) {
			$request->icons = \maybe_unserialize($request->icons);
		}

		if (!empty($request->sections)) {
			foreach ($request->sections as $key => $section) {
				$request->$key = (array) $section;
			}
		}

		return $request;
	}

	/**
	 * Calls the API and, if successfull, returns the object delivered by the API.
	 * @since 2.0.0
	 * @version EDD/1.9.1
	 *
	 * @param  array $data Parameters for the API action.
	 * @return false|object|void
	 */
	public function apiRequest(array $data) {
		// prettier-ignore
		if ($data['slug'] !== $this->plugin->eddSlug()) return;

		// Don't allow a plugin to ping itself
		// prettier-ignore
		if (\trailingslashit(\home_url()) === $this->plugin->eddStore()) return false;

		// prettier-ignore
		if ($this->requestRecentlyFailed()) return false;

		return $this->getVersionFromRemote();
	}

	/**
	 * Get the version info from the cache, if it exists.
	 */
	public function getCachedVersionInfo($cache_key = '') {
		// prettier-ignore
		if (empty($cache_key)) $cache_key = 'motionpage_sl_' . $this->licenseHash();

		$cache = $this->getOption($cache_key, []);

		// Cache is expired
		// prettier-ignore
		if (empty($cache['timeout']) || time() > $cache['timeout']) return '';

		// We need to turn the icons into an array, thanks to WP Core forcing these into an object at some point.
		$cache['value'] = json_decode($cache['value'], null, 512, JSON_THROW_ON_ERROR);
		if (!empty($cache['value']->icons)) {
			$cache['value']->icons = (array) $cache['value']->icons;
		}

		return $cache['value'];
	}

	/**
	 * Gets the plugin's tested version.
	 * @since 2.0.0
	 * @version EDD/1.9.2
	 * @return null|string
	 */
	private function getTestedVersion(object $version_info) {
		// There is no tested version.
		// prettier-ignore
		if (empty($version_info->tested)) return null;

		// Strip off extra version data so the result is x.y or x.y.z.
		$current_wp_version = explode('-', \get_bloginfo('version'))[0];

		// The tested version is greater than or equal to the current WP version, no need to do anything.
		if (version_compare($version_info->tested, $current_wp_version, '>=')) {
			return $version_info->tested;
		}

		$current_version_parts = explode('.', $current_wp_version);
		$tested_parts = explode('.', $version_info->tested);

		// The current WordPress version is x.y.z, so update the tested version to match it.
		if (
			isset($current_version_parts[2]) &&
			$current_version_parts[0] === $tested_parts[0] &&
			$current_version_parts[1] === $tested_parts[1]
		) {
			$tested_parts[2] = $current_version_parts[2];
		}

		return implode('.', $tested_parts);
	}

	/**
	 * Adds the plugin version information to the database. Expires in 12 hours.
	 * @since 2.0.0
	 * @version EDD/1.9.1
	 */
	public function setVersionInfoCache($value = '', $cache_key = ''): void {
		// prettier-ignore
		if (empty($cache_key)) $cache_key = 'motionpage_sl_' . $this->licenseHash();

		$data = [
			'timeout' => strtotime('+12 hours', time()),
			'value' => \wp_json_encode($value)
		];

		$this->updateOption($cache_key, $data);

		// Delete the duplicate option
		$this->deleteOption('motionpage_api_request_' . $this->licenseHash());
	}

	/**
	 * Get repo API data from store / Save to cache
	 * @since 2.0.0
	 * @version EDD/1.9.1
	 * @return \stdClass
	 */
	public function getRepoApiData() {
		$version_info = $this->getCachedVersionInfo();

		if (!$version_info) {
			$version_info = $this->apiRequest(['slug' => $this->plugin->eddSlug()]);

			// prettier-ignore
			if (!$version_info) return false;

			// This is required for your plugin to support auto-updates in WordPress 5.5.
			$version_info->plugin = MOTIONPAGE_BASENAME;
			$version_info->id = MOTIONPAGE_BASENAME;
			$version_info->tested = $this->getTestedVersion($version_info);

			$this->setVersionInfoCache($version_info);
		}

		return $version_info;
	}

	/**
	 * Gets the plugins active in a multisite network.
	 * @since 2.0.0
	 * @version EDD/1.9.1
	 */
	public function getActivePlugins(): array {
		$active_plugins = (array) $this->getOption('active_plugins', []);
		$active_network_plugins = (array) \get_site_option('active_sitewide_plugins', []);
		return array_merge($active_plugins, array_keys($active_network_plugins));
	}
}
