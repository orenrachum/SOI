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

namespace motionpage\Common\Utils;

/**
 * Class Development
 *
 * @package motionpage\Common\Utils
 *
 * @author	Swashata Ghosh <swashata4u@gmail.com>
 * @link		https://wpack.io/
 * @since		2.0.0
 * @version 2.1.0
 */

class Development {
	/**
	 * Manifest cache to prevent multiple reading from filesystem.
	 */
	private static array $manifestCache = [];

	/**
	 * Get manifest from cache or from file.
	 * @throws \LogicException If manifest file is not found.
	 */
	public static function getManifest(string $dist_path): array {
		if (!empty(self::$manifestCache)) {
			return self::$manifestCache;
		}

		$filepath = $dist_path . 'manifest.json';

		if (!file_exists($filepath)) {
			throw new \LogicException(sprintf('Manifest %s does not exist.', $filepath));
		}

		$manifest = json_decode(file_get_contents($filepath), true, 512, JSON_THROW_ON_ERROR);

		if ($manifest === null) {
			throw new \LogicException(sprintf('Invalid manifest file at %s.', $filepath));
		}

		self::$manifestCache = $manifest;
		return self::$manifestCache;
	}

	private static function getHandle(string $string): string {
		return 'mp-' . preg_replace('/\.(js|css)$/', '', $string);
	}

	/**
	 * Get handle and Url of all assets from the entrypoint.
	 *
	 * It doesn't enqueue anything for you, rather returns an associative array
	 * with handles and urls. You should use it to enqueue it on your own.
	 *
	 * @throws \LogicException If the entrypoint is not found in the manifest.
	 *
	 * @param string $entryPoint Which entrypoint would you like to enqueue.
	 * @param array  $config Additional configuration.
	 * @return array{css: array<int, array{handle: string, url: string}>, js: array<int, array{handle: string, url: string}>} Associative with `css` and `js`. Each of them are arrays containing ['handle' => string, 'url' => string].
	 */
	public static function getAssets(string $entryPoint, array $config): array {
		if (empty($config['dist_url']) || empty($config['dist_path'])) {
			throw new \LogicException('No dist folder found in the config');
		}

		$manifest = self::getManifest($config['dist_path']);

		if (!isset($manifest['entrypoints'][$entryPoint]['assets'])) {
			throw new \LogicException('No entry point found in the manifest');
		}

		$enqueue = $manifest['entrypoints'][$entryPoint]['assets'];

		$js_handles = [];
		$css_handles = [];

		// Figure out all javascript assets
		if ($config['js'] && isset($enqueue['js']) && count((array) $enqueue['js'])) {
			foreach ($enqueue['js'] as $js) {
				$js_handles[] = [
					'handle' => self::getHandle($js),
					'url' => $config['dist_url'] . $js
				];
			}
		}

		// Figure out all css assets
		if ($config['css'] && isset($enqueue['css']) && count((array) $enqueue['css'])) {
			foreach ($enqueue['css'] as $css) {
				$css_handles[] = [
					'handle' => self::getHandle($css),
					'url' => $config['dist_url'] . $css
				];
			}
		}

		return [
			'css' => $css_handles,
			'js' => $js_handles
		];
	}

	/**
	 * Enqueue all the assets for an entrypoint inside a source.
	 *
	 * @throws \LogicException If manifest.json is not found in the directory.
	 *
	 * @param string $entryPoint Which entrypoint would you like to enqueue.
	 * @param array  $config Additional configuration.
	 * @return array{css: array<int, array{handle: string, url: string}>, js: array<int, array{handle: string, url: string}>} Associative with `css` and `js`. Each of them are arrays containing ['handle' => string, 'url' => string].
	 */
	public static function enqueue(string $entryPoint, array $config): array {
		$assets = self::getAssets($entryPoint, $config);
		$js_deps = [];
		$css_deps = [];

		// Register JS files
		if ($config['js']) {
			foreach ($assets['js'] as $js) {
				\wp_enqueue_script(
					$js['handle'],
					$js['url'],
					[...$config['js_dep'], ...$js_deps],
					MOTIONPAGE_VERSION,
					$config['in_footer']
				);
				// The next one depends on this one
				$js_deps[] = $js['handle'];
			}
		}

		// Register CSS files
		if ($config['css']) {
			foreach ($assets['css'] as $css) {
				\wp_enqueue_style(
					$css['handle'],
					$css['url'],
					[...$config['css_dep'], ...$css_deps],
					MOTIONPAGE_VERSION,
					$config['media']
				);
				// The next one depends on this one
				$css_deps[] = $css['handle'];
			}
		}

		return $assets;
	}
}
