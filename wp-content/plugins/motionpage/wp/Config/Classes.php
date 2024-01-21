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

/**
 * This array is being used in ../Scaffold.php to instantiate the classes
 *
 * @package motionpage\Config
 * @since 2.0.0
 */
final class Classes {
	/**
	 * Init the classes inside these folders based on type of request.
	 * @see Requester for all the type of requests or to add your own
	 */
	public static function get(): array {
		return [
			['init' => 'App\\General'],
			[
				'init' => 'App\\Frontend',
				'on_request' => 'frontend'
			],
			[
				'init' => 'App\\Backend',
				'on_request' => 'backend',
				'exclude' => '\\Enqueue'
			],
			[
				'init' => 'App\\Backend\\Enqueue',
				'on_request' => 'is_builder'
			],
			[
				'init' => 'App\\Rest\\RestBuilder',
				'on_request' => 'rest_builder'
			],
			[
				'init' => 'App\\Rest',
				'on_request' => 'rest'
			],
			[
				'init' => 'Compatibility\\SaveSync',
				'on_request' => 'other_builders'
			],
			['init' => 'Compatibility\\Optimizers']
			//[
			//	'init' => 'App\\Cron',
			//	'on_request' => 'cron'
			//]
		];
	}
}
