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

namespace motionpage\Compatibility;

/**
 * @since 2.0.0
 */
class Optimizers {
	public function init(): void {
		/**
		 * Compatibility classes instantiates after anything else
		 * @see Scaffold::__construct
		 */
		\add_filter('sgo_js_minify_exclude', [$this, 'excludeIframe']);
		\add_filter('sgo_js_async_exclude', [$this, 'excludeIframe']);
		\add_filter('sgo_javascript_combine_exclude', [$this, 'excludeIframe']);

		\add_filter('rocket_delay_js_exclusions', function ($excluded_strings = []) {
			$excluded_strings[] = '/motionpage/assets/js/gsap/(.*)';
			$excluded_strings[] = '/motionpage/assets/js/(.*)';
			return $excluded_strings;
		});

		\add_filter('wpmeteor_exclude', [$this, 'handleWpMeteor'], null, 2);
	}

	/**
	 * SiteGround optimizer compatibility
	 * @since 2.0.0
	 */
	public function excludeIframe(array $exclude_list): array {
		$exclude_list[] = 'mp-embed';
		$exclude_list[] = 'motionpage-embed';
		$exclude_list[] = 'hypewolf/motionpage/embed';
		return $exclude_list;
	}

	/**
	 * WP Meteor optimizer exclude
	 * @since 2.1.0
	 */
	public function handleWpMeteor($exclude, $content) {
		if (preg_match('/motionpage/', $content)) {
			return true;
		}

		return $exclude;
	}
}
