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

use motionpage\Config\Plugin;

/**
 * Utility to show prettified wp_die errors, write debug logs as
 * string or array and to deactivate plugin and print a notice
 *
 * @package motionpage\Common\Utils
 * @since		2.0.0
 */
class Errors {
	/**
	 * Get the plugin data in static form
	 * @since 2.0.0
	 */
	public static function getPluginData(): array {
		return Plugin::init()->data();
	}

	/**
	 * Prettified wp_die error function
	 *
	 * @param $message : The error message
	 * @param string $subtitle : Specified title of the error
	 * @param string $source : File source of the error
	 * @param string $title : General title of the error
	 * @param string $exception
	 * @since 2.0.0
	 */
	public static function wpDie(
		$message = '',
		$subtitle = '',
		$source = '',
		$exception = '',
		$title = ''
	): void {
		if ($message) {
			$plugin = self::getPluginData();
			$title =
				$title ?:
				$plugin['name'] .
					' ' .
					$plugin['version'] .
					' ' .
					\__('&rsaquo; Fatal Error', $plugin['text-domain']);
			self::writeLog([
				'title' => $title . ' - ' . $subtitle,
				'message' => $message,
				'source' => $source,
				'exception' => $exception
			]);
			$source = $source
				? '<code>' .
					sprintf(/* translators: %s: file path */ \__('Error source: %s', $plugin['text-domain']), $source) .
					'</code><BR><BR>'
				: '';
			$footer = $source . '<a href="' . $plugin['uri'] . '">' . $plugin['uri'] . '</a>';
			$message = '<p>' . $message . '</p>';
			$message .= $exception ? '<p><strong>Exception: </strong><BR>' . $exception . '</p>' : '';
			$message = sprintf(
				'<h1>%s<br><small>%s</small></h1>%s<hr><p>%s</p>',
				$title,
				$subtitle,
				$message,
				$footer
			);
			\wp_die($message, $title);
		} else {
			\wp_die();
		}
	}

	/**
	 * De-activates the plugin and shows notice error in back-end
	 *
	 * @param $message : The error message
	 * @param string $subtitle : Specified title of the error
	 * @param string $source : File source of the error
	 * @param string $title : General title of the error
	 * @param string $exception
	 * @since 2.0.0
	 */
	public static function pluginDie(
		$message = '',
		$subtitle = '',
		$source = '',
		$exception = '',
		$title = ''
	): void {
		if ($message) {
			$plugin = self::getPluginData();
			$title =
				$title ?:
				$plugin['name'] .
					' ' .
					$plugin['version'] .
					' ' .
					\__('&rsaquo; Plugin Disabled', $plugin['text-domain']);
			self::writeLog([
				'title' => $title . ' - ' . $subtitle,
				'message' => $message,
				'source' => $source,
				'exception' => $exception
			]);
			$source = $source
				? '<small>' .
					sprintf(/* translators: %s: file path */ \__('Error source: %s', $plugin['text-domain']), $source) .
					'</small> - '
				: '';
			$footer = $source . '<a href="' . $plugin['uri'] . '"><small>' . $plugin['uri'] . '</small></a>';
			$error = sprintf(
				'<strong><h3>%s</h3>%s</strong><p>%s</p><hr><p>%s</p>',
				$title,
				$subtitle,
				$message,
				$footer
			);
			global $motionpage_die_notice;
			$motionpage_die_notice = $error;
			\add_action('admin_notices', static function (): void {
				global $motionpage_die_notice;
				echo \wp_kses_post(
					sprintf('<div class="notice notice-error"><p>%s</p></div>', $motionpage_die_notice)
				);
			});
		}

		\add_action('admin_init', static function (): void {
			\deactivate_plugins(MOTIONPAGE_BASENAME, false, null);
			if (isset($_GET['activate'])) {
				unset($_GET['activate']);
			}

			$admin_mail = motionpage()->getOption('admin_email', '');
			if ($admin_mail) {
				global $motionpage_die_notice;
				\wp_mail($admin_mail, 'Motion.page Error: plugin was deactivated!', $motionpage_die_notice);
			}
		});
	}

	/**
	 * Writes a log if wp_debug is enables
	 * @since 2.0.0
	 */
	public static function writeLog($log): void {
		if (true === WP_DEBUG && self::getPluginData()['development']) {
			//file_put_contents('wp-content/debug.log', '');
			if (is_string($log)) {
				error_log($log);
			} elseif (is_array($log) || is_object($log) || $log instanceof \stdClass) {
				error_log(print_r($log, true));
			} else {
				error_log(gettype($log));
			}
		}
	}

	/**
	 * Throws an exception with set_error_handler during API calls
	 * @since 2.0.0
	 * @return never
	 */
	// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	public static function handleApiErrors(int $errNo, string $errMsg, string $file, int $line) {
		//self::writeLog($errMsg);
		throw new \Exception($errMsg);
	}
}
