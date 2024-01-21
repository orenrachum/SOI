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

namespace motionpage\App\Frontend;

use motionpage\Common\Abstracts\Base;

/**
 * Helpers
 *
 * @package motionpage\App\Frontend
 * @since 2.0.0
 */
class Helpers extends Base {
	/**
	 * Initialize the class.
	 * @since 2.0.0
	 */
	public function init(): void {
		/**
		 * This backend class is only being instantiated in the backend as requested in the Scaffold class
		 *
		 * @see Requester::isFrontend()
		 * @see Scaffold::__construct
		 *
		 * Add plugin code here for frontend helpers specific functions
		 */
		\add_filter('script_loader_tag', [$this, 'filterScripts'], 0, 2);
		//\add_action('wp_head', [$this, 'oxygenReusable'], 9999999);
	}

	/**
	 * Filter script attributes
	 * @since 2.0.0
	 */
	public function filterScripts(string $tag, string $handle): string {
		$is_mp_script = strpos($handle, 'mp-') === 0;
		$cfasync = \wp_scripts()->get_data($handle, 'cfasync');
		$module = \wp_scripts()->get_data($handle, 'module');

		if ($is_mp_script || $cfasync) {
			$tag = str_replace('<script ', '<script data-cfasync="false" ', $tag);
		}

		if ($module) {
			$tag = str_replace('<script ', '<script type="module" ', $tag);
		}

		$cookieconsent = \apply_filters('motionpage/utils/cookieconsent', false);
		if ($cookieconsent && $is_mp_script) {
			$tag = str_replace('<script ', '<script data-cookieconsent="ignore" ', $tag);
		}

		if (defined('SPEEDIEN_API_URL') && $is_mp_script) {
			$tag = str_replace('<script ', '<script data-wpspdn-nooptimize="true" ', $tag);
		}

		return $tag;
	}

	/**
	 * Preview Oxygen Reusable Content
	 * @since   1.5.0
	 */
	public function oxygenReusable(): void {
		if (!empty($_GET['motionpage_oxy_reusable'] ?? '')) {
			$reusable_id = $_GET['motionpage_oxy_reusable'];
			$sanitize = filter_var($reusable_id, FILTER_SANITIZE_NUMBER_INT);

			// component-init.php [oxygen]
			//$oxygen_vsb_css_styles = new \WP_Styles();
			//$oxygen_vsb_css_styles->add('oxygen-styles', ct_get_current_url('xlink=css'));
			//$oxygen_vsb_css_styles->enqueue(['oxygen-styles']);
			//$oxygen_vsb_css_styles->do_items();
			//ct_css_output

			echo \do_shortcode(\get_post_meta($sanitize, 'ct_builder_shortcodes', true));
			die();
		}
	}
}
