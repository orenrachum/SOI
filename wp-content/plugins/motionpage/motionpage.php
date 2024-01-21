<?php

use motionpage\Scaffold;
use motionpage\Common\Functions;
use motionpage\Common\UpdaterFunctions;

/**
 * @package     motionpage
 * @author    	HypeWolf OÜ <hello@hypewolf.com>
 * @copyright 	2023 HypeWolf OÜ
 * @license   	EULA + GPLv2
 * @link      	https://motion.page
 * @version  		2.1.6
 *
 * @wordpress-plugin
 *
 * Plugin Name:   Motion.page
 * Plugin URI:    https://motion.page
 * Description:   Move it like it's HOT!
 * Version:       2.1.6
 * Author:        HypeWolf OÜ
 * Author URI:    https://hypewolf.com
 * Text Domain:   motionpage
 * Domain Path:   /languages
 * License:       EULA + GPLv2
 * License URI:   https://motion.page/eula + https://www.gnu.org/licenses/gpl-2.0.html
 * Requires PHP:  7.4
 * Requires WP:   5.9.0
 * Namespace:     motionpage
 */

if (!defined('ABSPATH')) {
	exit();
}

define('MOTIONPAGE_NAME', 'motionpage');
define('MOTIONPAGE_VERSION', '2.1.6');
define('MOTIONPAGE_FILE', __FILE__);
define('MOTIONPAGE_BASENAME', plugin_basename(MOTIONPAGE_FILE));
define('MOTIONPAGE_DIR_PATH', plugin_dir_path(MOTIONPAGE_FILE));
define('MOTIONPAGE_DIR_URL', plugin_dir_url(MOTIONPAGE_FILE));

$motionpage_autoloader = require MOTIONPAGE_DIR_PATH . 'vendor/autoload.php';

if (!wp_installing()) {
	register_activation_hook(__FILE__, ['motionpage\Config\Setup', 'activation']);
	register_deactivation_hook(__FILE__, ['motionpage\Config\Setup', 'deactivation']);
	register_uninstall_hook(__FILE__, ['motionpage\Config\Setup', 'uninstall']);
}

if (!class_exists('\motionpage\Scaffold')) {
	wp_die(__('Motion.page is unable to find the Scaffold class.', MOTIONPAGE_NAME));
}

add_action('plugins_loaded', static function () use ($motionpage_autoloader): void {
	try {
		new Scaffold($motionpage_autoloader);
	} catch (Exception $e) {
		wp_die(__('Motion.page is unable to run the Scaffold class.', MOTIONPAGE_NAME));
	}
});

add_action('wp_initialize_site', ['motionpage\Config\Setup', 'newMultisiteBlog'], 999, 2);

/**
 * Create a main function for external uses
 * @since 2.0.0
 */
function motionpage(): Functions {
	return new Functions();
}

/**
 * Create a updater function for external uses
 * @since 2.0.0
 */
function motionpageUpdater(): UpdaterFunctions {
	return new UpdaterFunctions();
}
