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

namespace motionpage\Config;

use motionpage\Common\Abstracts\Base;
use motionpage\Common\Utils\Errors;
use motionpage\Common\Utils\Requirements\RWFile;

/**
 * Check if any requirements are needed to run this plugin. We use the
 * "Requirements" package from "MicroPackage" to check if any PHP Extensions,
 * plugins, themes or PHP/WP version are required.
 * @docs https://github.com/micropackage/requirements
 *
 * @package motionpage\Config
 * @since 2.0.0
 */
final class Requirements extends Base {
	/** @var \Micropackage\Requirements\Requirements|mixed */
	public $requirements;

	/**
	 * Specifications for the requirements
	 * @since 2.0.0
	 */
	public function specifications(): array {
		return \apply_filters('motionpage/lib/requirements', [
			'php' => $this->plugin->requiredPHP(),
			'php_extensions' => [],
			'wp' => $this->plugin->requiredWP(),
			'plugins' => [],
			'RWFile' => true
		]);
	}

	/**
	 * Plugin requirements checker
	 * @since 2.0.0
	 */
	public function check(): bool {
		if (class_exists('\Micropackage\Requirements\Requirements')) {
			$this->requirements = new \Micropackage\Requirements\Requirements(
				$this->plugin->name(),
				$this->specifications()
			);

			$this->requirements->register_checker(RWFile::class);
			if (!$this->requirements->satisfied()) {
				$this->requirements->print_notice();
				Errors::pluginDie();
				return false;
			}

			return true;
		}

		// Else we do a version check based on version_compare
		$is_not_error = $this->versionCompare();
		return $is_not_error;
	}

	/**
	 * Compares PHP & WP versions and kills plugin if it's not compatible
	 * @since 2.0.0
	 */
	public function versionCompare(): bool {
		foreach (
			[
				// PHP version check
				[
					'current' => phpversion(),
					'compare' => $this->plugin->requiredPHP(),
					'title' => \__('Invalid PHP version', $this->plugin->textDomain()),
					'message' => sprintf(
						/* translators: %1$1s: required php version, %2$2s: current php version */
						\__(
							'You must be using PHP %1$1s or greater. You are currently using PHP %2$2s.',
							$this->plugin->textDomain()
						),
						$this->plugin->requiredPHP(),
						phpversion()
					)
				],
				// WP version check
				[
					'current' => \get_bloginfo('version'),
					'compare' => $this->plugin->requiredWP(),
					'title' => \__('Invalid WordPress version', $this->plugin->textDomain()),
					'message' => sprintf(
						/* translators: %1$1s: required wordpress version, %2$2s: current wordpress version */
						\__(
							'You must be using WordPress %1$1s or greater. You are currently using WordPress %2$2s.',
							$this->plugin->textDomain()
						),
						$this->plugin->requiredWP(),
						\get_bloginfo('version')
					)
				]
			]
			as $compat_check
		) {
			if (version_compare($compat_check['compare'], $compat_check['current'], '>=')) {
				Errors::pluginDie($compat_check['message'], $compat_check['title'], MOTIONPAGE_BASENAME);
				return false;
			}

			return true;
		}
	}
}
