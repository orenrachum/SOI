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
use motionpage\Common\Utils\Development;

/**
 * Class Enqueue
 *
 * @package motionpage\App\Frontend
 * @since 2.0.0
 */
class Enqueue extends Base {
	/**
	 * Check if overflow-x is set inside advanced => motionpage
	 * @since   1.0.0
	 */
	private bool $is_overflow = false;

	/**
	 * Check if we need CDN for GSAP
	 * @since   1.0.0
	 */
	private bool $is_cdn = false;

	/**
	 * Check the value of debugMode
	 * @since   1.0.0
	 */
	private bool $is_debug_mode = false;

	/**
	 * Check the value of hashAnchorLinks
	 * @since   2.1.0
	 */
	private bool $hashAnchorLinks = false;

	/**
	 * Save fetched animations as variable
	 * @since   1.4.0
	 */
	private array $scripts_holder = [];

	/**
	 * Check if we need ScrollSmoother
	 * @since   1.5.0
	 */
	private bool $is_scrollSmoother = false;

	/**
	 * ScrollSmoother code
	 * @since   1.5.0
	 */
	private string $scrollSmoother = '';

	/**
	 * ScrollSmoother FixedSticky
	 * @since   2.0.0
	 */
	private int $fixedSticky = 0;

	/**
	 * Prevent scripts from loading in other builders
	 * @since   1.4.0
	 */
	private bool $not_builders = true;

	/**
	 * CookieBot Ignore Filter - Cookie Consent : https://www.cookiebot.com/en/
	 * @since   2.0.0
	 */
	private bool $cookieconsent = false;

	/**
	 * Poylang translation sync
	 * @since   2.1.0
	 */
	private bool $polylangSync = false;

	/**
	 * WPML translation sync
	 * @since   2.1.0
	 */
	private bool $wpmlSync = false;

	/**
	 * WPML translation sync
	 * @since   2.1.0
	 */
	private bool $dssa = false;

	/**
	 * @since 2.0.0
	 */
	public function init(): void {
		/**
		 * This frontend class is only being instantiated in the frontend as requested in the Scaffold class
		 *
		 * @see Requester::isFrontend()
		 * @see Scaffold::__construct
		 */
		\add_action('init', [$this, 'iframeDefine'], 1);
		\add_action('init', [$this, 'settings'], 10);

		\add_action('wp_loaded', [$this, 'register'], 0);
		\add_action('wp_head', [$this, 'head'], 0);
		\add_action('wp_enqueue_scripts', [$this, 'frontend'], 999);

		\add_action(
			'send_headers',
			function (): void {
				if (\is_ssl() && defined('MOTIONPAGE_IFRAME')) {
					$coep_filter = \apply_filters('motionpage/utils/requireCorp', false);
					if (motionpage()->canUseCoep()) {
						header('Cross-Origin-Embedder-Policy: credentialless');
					} elseif ($coep_filter) {
						header('Cross-Origin-Embedder-Policy: require-corp');
					}
				}
			},
			1
		);
	}

	/**
	 * Check if we are in the iframe and if so, define MOTIONPAGE_IFRAME
	 * @access  public
	 * @since   1.0.0
	 */
	public function iframeDefine(): void {
		if (!empty($_GET['motionpage_iframe'] ?? '')) {
			define('MOTIONPAGE_IFRAME', true);
		}
	}

	public function settings(): void {
		$this->cookieconsent = \apply_filters('motionpage/utils/cookieconsent', false);

		$main_settings = motionpage()->getMainSettings();
		if (!empty($main_settings)) {
			$hashFix = ($main_settings['scrollSmoother']['isOpen'] ?? 0) === 1;
			$this->is_cdn = ($main_settings['cdn'] ?? 0) === 1;
			$this->is_overflow = ($main_settings['advanced']['overflow'] ?? 0) === 1;
			$this->is_debug_mode = ($main_settings['advanced']['debugMode'] ?? 0) === 1;
			$this->hashAnchorLinks = !$hashFix && ($main_settings['advanced']['hashAnchorLinks'] ?? 0) === 1;
			$this->polylangSync = ($main_settings['system']['polylang'] ?? 0) === 1;
			$this->wpmlSync = ($main_settings['system']['wpml'] ?? 0) === 1;
			$this->dssa =
				!\defined('MOTIONPAGE_IFRAME') &&
				\current_user_can('manage_options') &&
				($main_settings['advanced']['dssa'] ?? 0) === 1;

			if (
				!$this->dssa &&
				($main_settings['scrollSmoother']['isOpen'] ?? 0) === 1 &&
				!empty($main_settings['scrollSmoother']['code'] ?? '')
			) {
				$this->is_scrollSmoother = true;
				$this->scrollSmoother = htmlspecialchars_decode($main_settings['scrollSmoother']['code'], ENT_QUOTES);
				$this->fixedSticky = $main_settings['scrollSmoother']['fixedSticky'] ?? 0;
			}
		}
	}

	public function register(): void {
		$is_oxygen = defined('SHOW_CT_BUILDER') || defined('OXYGEN_IFRAME');
		$is_bricks =
			isset($_GET['bricksbuilder']) ||
			(function_exists('bricks_is_builder_iframe') && \bricks_is_builder_iframe());
		$is_divi = isset($_GET['et_fb']);
		$is_beaver = isset($_GET['fl_builder']);
		$is_brizzy = isset($_GET['brizy-edit-iframe']);

		if (\is_customize_preview() || $is_oxygen || $is_beaver || $is_divi || $is_bricks || $is_brizzy) {
			$this->not_builders = false;
		}

		$js_route = MOTIONPAGE_DIR_URL . 'assets/js/';
		$gsap_route = $js_route . 'gsap/';
		$gsap_route_cdn = $js_route . 'gsap/';
		$version = $this->plugin->version();
		$gsap_version = $this->plugin->gsapVersion();

		if ($this->is_cdn) {
			$cf_route = 'https://cdnjs.cloudflare.com/ajax/libs/gsap/';
			$gsap_route_cdn = $cf_route . $gsap_version . '/';
		}

		\wp_register_script('mp-ImageSequence', $js_route . 'ImageSequence.js', ['mp-gsap'], $version, true);
		\wp_script_add_data('mp-ImageSequence', 'module', true);

		\wp_register_script('mp-MouseMove', $js_route . 'MouseMove.js', ['mp-gsap'], $version, true);

		\wp_register_script('mp-gsap', $gsap_route_cdn . 'gsap.min.js', [], $gsap_version, true);
		\wp_register_script('mp-Flip', $gsap_route_cdn . 'Flip.min.js', ['mp-gsap'], $gsap_version, true);

		\wp_register_script(
			'mp-ScrollTrigger',
			$gsap_route_cdn . 'ScrollTrigger.min.js',
			['mp-gsap'],
			$gsap_version,
			true
		);

		\wp_register_script('mp-Observer', $gsap_route_cdn . 'Observer.min.js', ['mp-gsap'], $gsap_version, true);
		\wp_register_script('mp-SplitText', $gsap_route . 'SplitText.min.js', ['mp-gsap'], $gsap_version, true);
		\wp_register_script('mp-DrawSVG', $gsap_route . 'DrawSVGPlugin.min.js', ['mp-gsap'], $gsap_version, true);
		\wp_register_script(
			'mp-ScrollToPlugin',
			$gsap_route . 'ScrollToPlugin.min.js',
			['mp-gsap'],
			$gsap_version,
			true
		);

		\wp_register_script(
			'mp-CSSRulePlugin',
			$gsap_route . 'CSSRulePlugin.min.js',
			['mp-gsap'],
			$gsap_version,
			true
		);

		\wp_register_script('mp-CustomEase', $gsap_route . 'CustomEase.min.js', ['mp-gsap'], $gsap_version, true);

		\wp_register_script(
			'mp-ScrollSmoother',
			$gsap_route . 'ScrollSmoother.min.js',
			['mp-ScrollTrigger'],
			$gsap_version,
			true
		);

		\wp_register_script(
			'mp-MotionPathPlugin',
			$gsap_route . 'MotionPathPlugin.min.js',
			['mp-gsap'],
			$gsap_version,
			true
		);

		\wp_register_script(
			'mp-MotionPathHelper',
			$gsap_route . 'MotionPathHelper.min.js',
			['mp-MotionPathPlugin'],
			$gsap_version,
			true
		);

		\wp_register_script(
			'mp-ScrambleText',
			$gsap_route . 'ScrambleTextPlugin.min.js',
			['mp-gsap'],
			$gsap_version,
			true
		);

		\wp_register_script('mp-GSDevTools', $gsap_route . 'GSDevTools.min.js', ['mp-gsap'], $gsap_version, true);
	}

	/**
	 * @since   1.0.0
	 */
	public function head(): void {
		$POST_ID = motionpage()->getPostID();
		$prevent_load = motionpage()->preventLoad($POST_ID);

		$version = $this->plugin->version();

		if (!$prevent_load && $this->not_builders) {
			$wp_meteor = class_exists('\\WP_Meteor\\Engine\\Initialize') ? ' data-wpmeteor-nooptimize="true"' : '';
			echo <<<HTML
			<script{$wp_meteor}>window.MOTIONPAGE_FRONT={version:"{$version}"}</script>
			HTML;

			$timelines = motionpage()->getAllScripts($POST_ID);
			$this->scripts_holder = $timelines;

			if (!defined('MOTIONPAGE_IFRAME') && !empty($timelines)) {
				$fouc = \apply_filters('motionpage/utils/fouc', false, $POST_ID);
				if (!$fouc) {
					foreach ($timelines as $timeline) {
						$is_live = motionpage()->getIsLive($timeline, $POST_ID);

						if ($is_live) {
							$cc_code = $this->cookieconsent ? ' data-cookieconsent="ignore"' : '';
							$speedien = defined('SPEEDIEN_API_URL') ? ' data-wpspdn-nooptimize="true"' : '';
							echo <<<HTML
							<style>body{visibility:hidden;}</style>
							<script data-cfasync="false"{$cc_code}{$speedien}>addEventListener("DOMContentLoaded",()=>(document.body.style.visibility="inherit"));</script>
							<noscript><style>body{visibility:inherit;}</style></noscript>
							HTML;
							break;
						}
					}
				}
			}
		}
	}

	/**
	 * @since   1.0.0
	 */
	public function frontend(): void {
		$POST_ID = motionpage()->getPostID();
		$prevent_load = motionpage()->preventLoad($POST_ID);
		$carrier = [];
		$pageExit = false;
		$has_splitText = false;
		$has_gensel = false;
		$polylang_sync = \apply_filters('motionpage/utils/polylang', $this->polylangSync);
		$wpml_sync = \apply_filters('motionpage/utils/wpml', $this->wpmlSync);

		if (!$prevent_load && $this->not_builders) {
			// TODO : Replace this with custom page
			if (preg_match('/undefined\b/', \home_url($_SERVER['REQUEST_URII'] ?? ''))) {
				\wp_redirect(\home_url(), 307);
				exit();
			}

			// TODO : ScrollSmoother wihtout timelines
			if (!empty($this->scripts_holder)) {
				$bypass_reduced_motion = '';
				$script_prefix = '';
				$script = '';

				//if (defined('BRICKS_DB_TEMPLATE_SLUG')) {
				//  var_dump(\Bricks\Templates::get_template_type(1793));
				//}

				foreach ($this->scripts_holder as $script_holder) {
					$show_on_front = motionpage()->getIsLive($script_holder, $POST_ID);

					if ($polylang_sync && !$show_on_front) {
						if (function_exists('pll_current_language') && function_exists('pll_languages_list')) {
							$current_language = \pll_current_language('slug');
							$languages = \pll_languages_list();
							$languages = array_diff($languages, [$current_language]);
							if (function_exists('pll_get_post')) {
								foreach ($languages as $language) {
									$translated = \pll_get_post($POST_ID, $language);
									if ($translated) {
										$show_on_front = motionpage()->getIsLive($script_holder, $translated);
										if ($show_on_front) {
											break;
										}
									}
								}
							}
						}
					}

					if (
						$wpml_sync &&
						!$show_on_front &&
						function_exists('icl_object_id') &&
						function_exists('icl_get_languages')
					) {
						$current_language = \apply_filters('wpml_current_language', null);
						$languages = \apply_filters('wpml_active_languages', null, 'orderby=id&order=desc');

						foreach ($languages as $lang) {
							if ($lang['language_code'] == $current_language) {
								continue;
							}

							$translated = \apply_filters('wpml_object_id', $POST_ID, 'post', false, $lang['language_code']);

							if ($translated) {
								$show_on_front = motionpage()->getIsLive($script_holder, $translated);
								if ($show_on_front) {
									break;
								}
							}
						}
					}

					//\wp_enqueue_script('mp-Flip');
					//\wp_enqueue_script('mp-Observer');

					if ($show_on_front) {
						if (!wp_script_is('mp-gsap', 'enqueued')) {
							\wp_enqueue_script('mp-gsap');
							$carrier[] = 'mp-gsap';

							//\wp_localize_script('mp-gsap', 'HYPEWOLF', [
							//	'v' => $this->plugin->version()
							//]);

							if ($this->is_overflow) {
								$script_prefix .= 'document.body.style.overflowX="hidden";';
							}

							if (!defined('MOTIONPAGE_IFRAME')) {
								$script_prefix .=
									'gsap.registerPlugin({name:"transition",init(t,e,r){return this.target=t,this.tween=r,this.reverting=gsap.core.reverting||function(){},!!t.style},render(t,{target:e,tween:r,reverting:i}){e.style.transition=(1===r.progress()||!r._time&&i())&&"isFromStart"!==r.data?"":"unset"}});';

								$script_prefix .= 'gsap.defaults({duration:1,transition:"unset"});';
							}

							$is_debug_mode = $this->is_debug_mode ? 'true' : 'false';
							$script_prefix .= 'gsap.config({nullTargetWarn:' . $is_debug_mode . '});';
						}

						if (property_exists($script_holder, 'plugins') && $script_holder->plugins !== null) {
							if (
								!\wp_script_is('mp-SplitText', 'enqueued') &&
								strpos($script_holder->plugins, 'SplitText') !== false
							) {
								\wp_enqueue_script('mp-SplitText');
								$carrier[] = 'mp-SplitText';
								$has_splitText = true;
							}

							if (
								!$has_gensel &&
								$has_splitText &&
								!defined('MOTIONPAGE_IFRAME') &&
								strpos($script_holder->plugins, '_mp_GENSEL') !== false
							) {
								\wp_add_inline_script(
									'mp-SplitText',
									'function _mp_GENSEL(e){const n=[];for(;e.tagName;){let t=0;if(e.parentNode){const n=e.parentNode.children;for(;t<n.length&&n[t]!==e;)t++}n.unshift(e.nodeName+(t>0?`:nth-child(${t+1})`:"")),e=e.parentNode}return n.join(" > ")}',
									'before'
								);
								$has_gensel = true;
							}

							$plugin_names = ['MouseMove', 'CustomEase', 'DrawSVG', 'ImageSequence', 'ScrambleText'];
							foreach ($plugin_names as $plugin_name) {
								if (
									!\wp_script_is('mp-' . $plugin_name, 'enqueued') &&
									strpos($script_holder->plugins, $plugin_name) !== false
								) {
									\wp_enqueue_script('mp-' . $plugin_name);
									$carrier[] = 'mp-' . $plugin_name;
								}
							}

							if (!$pageExit && strpos($script_holder->plugins, 'pageExit') !== false) {
								$pageExit = true;
							}
						}

						if (strpos($script_holder->plugins, 'BypassReducedMotion') !== false) {
							$bypass_reduced_motion .= $script_holder->script_value;
							continue;
						}

						$script .= $script_holder->script_value;
					}
				}

				$can_load = $script || $bypass_reduced_motion || $this->is_scrollSmoother || $this->hashAnchorLinks;

				if (($can_load && $pageExit) || defined('MOTIONPAGE_IFRAME')) {
					echo <<<HTML
					<script>window.MOTIONPAGE_FRONT.attachExitClick= function (el, uid) {
						if (!el | !uid) return;
						if (top.mp_iframe) return;
						el.addEventListener("click", function (e) {
							e.preventDefault();
							window[uid].play().call(() => (location.href = void 0 !== this ? this : location.href));
						});
					}</script>
					HTML;
				}

				if ($can_load) {
					$reduce_motion = '';

					$legacy_b_scr = \apply_filters('motionpage_before_scripts', '', $POST_ID);
					$legacy_a_scr = \apply_filters('motionpage_after_scripts', '', $POST_ID);

					// ? REDUCED MOTION
					if ($script) {
						$remove_r_m = \apply_filters('motionpage/utils/bypassReduced', false, $POST_ID);

						if (!$remove_r_m) {
							$reduce_motion .= 'if(!matchMedia("(prefers-reduced-motion: reduce)").matches){';
						}

						$reduce_motion .= \apply_filters('motionpage/scripts/before/reduced', '', $POST_ID);
						$reduce_motion .= $script;
						$reduce_motion .= \apply_filters('motionpage/scripts/after/reduced', '', $POST_ID);
						if (!$remove_r_m) {
							$reduce_motion .= '}';
						}
					}

					$dcl_wrapper = 'addEventListener("DOMContentLoaded",()=>{';
					$dcl_wrapper .= $script_prefix;
					$dcl_wrapper .= \apply_filters('motionpage/scripts/before', $legacy_b_scr, $POST_ID);

					$scrolltrigger_loaded = false;

					if ($this->is_scrollSmoother) {
						$scrolltrigger_loaded = true;
						\wp_enqueue_script('mp-ScrollTrigger');
						$carrier[] = 'mp-ScrollTrigger';
						\wp_enqueue_script('mp-ScrollSmoother');
						$carrier[] = 'mp-ScrollSmoother';
						if ($this->fixedSticky) {
							$dcl_wrapper .=
								'Array.from(document.getElementsByTagName("*")).filter((e=>["fixed","sticky"].includes(getComputedStyle(e).position))).reverse().forEach((p=>{document.querySelector("body").prepend(p)}));';
						}

						$dcl_wrapper .= $this->scrollSmoother;
						$dcl_wrapper .= 'addEventListener("load",(()=>_$W?.ScrollSmoother?.get()?.refresh(!0)));';
					}

					$dcl_wrapper .= $reduce_motion;
					$dcl_wrapper .= $bypass_reduced_motion;
					$dcl_wrapper .= \apply_filters('motionpage/scripts/after', $legacy_a_scr, $POST_ID);

					if (strpos($dcl_wrapper, 'motionPath') !== false) {
						\wp_enqueue_script('mp-MotionPathPlugin');
						$carrier[] = 'mp-MotionPathPlugin';
					}

					// removed s as stripos is slower than strpos
					$is_scrollTrigger = strpos($dcl_wrapper, 'crollTrigger') !== false;
					if ($is_scrollTrigger && !$scrolltrigger_loaded) {
						$scrolltrigger_loaded = true;
						\wp_enqueue_script('mp-ScrollTrigger');
						$carrier[] = 'mp-ScrollTrigger';
					}

					if ($scrolltrigger_loaded) {
						$dcl_wrapper .=
							'_$W._mp_refresher=(t=0)=>{ScrollTrigger&&setTimeout((()=>{ScrollTrigger.sort(),ScrollTrigger.getAll().forEach((r=>r.refresh()))}),t)},addEventListener("load",(()=>_mp_refresher(92)));';

						if (\apply_filters('motionpage/utils/lazyloaded', false, $POST_ID)) {
							$dcl_wrapper .=
								'document.addEventListener("lazyloaded",()=>ScrollTrigger&&ScrollTrigger.refresh(true));';
						}
					}

					if ($this->is_debug_mode && !defined('MOTIONPAGE_IFRAME')) {
						$dcl_wrapper .=
							'console.log("%c Debug Mode", "font-weight: bold; font-size: 50px;color: red; text-shadow: 3px 3px 0 rgb(217,31,38) , 6px 6px 0 rgb(226,91,14) , 9px 9px 0 rgb(245,221,8) , 12px 12px 0 rgb(5,148,68) , 15px 15px 0 rgb(2,135,206) , 18px 18px 0 rgb(4,77,145) , 21px 21px 0 rgb(42,21,113)");';
						$dcl_wrapper .= 'console.log(gsap.getTweensOf("*"));';
						if ($is_scrollTrigger) {
							$dcl_wrapper .= 'if(ScrollTrigger)console.log(ScrollTrigger?.getAll());';
						}

						\wp_enqueue_script('mp-GSDevTools');
						$carrier[] = 'mp-GSDevTools';
						$dcl_wrapper .= 'GSDevTools.create();';
					}

					$dcl_wrapper .= '});';

					// TODO : WIP COOKIE
					//function _mp_setCookie(n, e = 30, p = "/") {
					//  const d = new Date();
					//  d.setTime(d.getTime() + e * 86400000);
					//  document.cookie = `${n}=1;expires=${d.toUTCString()};path=${p}`;
					//}

					//function _mp_setCookie(e,t=30,o="/"){const i=new Date;i.setTime(i.getTime()+864e5*t),document.cookie=`${e}=1;expires=${i.toUTCString()};path=${o}`}

					//addEventListener("pageshow", function (event) {
					//  const historyTraversal =
					//      event.persisted ||
					//      (typeof performance !== "undefined" && performance.navigation.type === 2) ||
					//      performance.getEntriesByType("navigation")[0].type === "back_forward";
					//  if (historyTraversal) location.reload();
					//});

					$exit_fix = \apply_filters('motionpage/utils/historyExit', true, $POST_ID) ?? true;
					if ($pageExit && $exit_fix) {
						$history_exit_fix =
							'addEventListener("pageshow",(function(e){(e.persisted||void 0!==performance&&2===performance.navigation.type||"back_forward"===performance.getEntriesByType("navigation")[0].type)&&location.reload()}));';
						$dcl_wrapper = $history_exit_fix . $dcl_wrapper;
					}

					if ($this->hashAnchorLinks) {
						\wp_enqueue_script('mp-ScrollToPlugin');
						$carrier[] = 'mp-ScrollToPlugin';
						$dcl_wrapper .=
							'addEventListener("DOMContentLoaded",(()=>{location.hash&&gsap.to(window, {duration:1,scrollTo:location.hash})}));';
					}

					if ($this->dssa) {
						$dcl_wrapper .=
							'console.warn("%cScrollSmoother is disabled for admin users!", "font: 11px Inconsolata, monospace;color: #41FF00;");';
					}

					\wp_add_inline_script(end($carrier), 'window._$W = window;' . $dcl_wrapper, 'after');
					\do_action('motionpage/action/front');
				}
			}
		}

		if (defined('MOTIONPAGE_IFRAME')) {
			$this->iframe();
		}

		if ($prevent_load && !defined('MOTIONPAGE_IFRAME')) {
			if (($_GET['debug'] ?? '') === 'scripts') {
				$version = $this->plugin->version();

				$scripts = [
					'mp-gsap',
					'mp-ScrollTrigger',
					'mp-ScrollSmoother',
					'mp-ScrollToPlugin',
					'mp-MotionPathPlugin',
					'mp-MotionPathHelper',
					'mp-Flip',
					'mp-Observer',
					'mp-SplitText',
					'mp-DrawSVG',
					'mp-CSSRulePlugin',
					'mp-CustomEase',
					'mp-ImageSequence',
					'mp-MouseMove',
					'mp-ScrambleText',
					'mp-GSDevTools'
				];

				foreach ($scripts as $script) {
					\wp_enqueue_script($script);
				}

				$code = 'window.MOTIONPAGE_FRONT={version:"' . $version . '"};';

				$code .=
					'console.log("%c Debug Mode", "font-weight: bold; font-size: 50px;color: red; text-shadow: 3px 3px 0 rgb(217,31,38) , 6px 6px 0 rgb(226,91,14) , 9px 9px 0 rgb(245,221,8) , 12px 12px 0 rgb(5,148,68) , 15px 15px 0 rgb(2,135,206) , 18px 18px 0 rgb(4,77,145) , 21px 21px 0 rgb(42,21,113)");';

				\wp_add_inline_script(end($scripts), 'window._$W = window;' . $code, 'after');
			}
		}
	}

	private function iframe(): void {
		\do_action('motionpage/action/iframe');

		\add_filter('js_do_concat', '__return_false'); // Page Optimize

		\wp_deregister_script('react');

		\add_filter('qm/dispatch/html', '__return_false');

		\remove_action('wp_head', '_admin_bar_bump_cb');
		\add_filter('show_admin_bar', '__return_false');
		\remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

		// Brindle QuickPop iframe fix
		if (defined('QP_PLUGIN_DIR')) {
			\wp_dequeue_script('qp-functions');
			\wp_dequeue_style('qp-styles');
			\wp_dequeue_style('qp-font-styles');
		}

		// Optimole JS/CSS fix
		\add_filter('optml_should_replace_page', '__return_true');

		// WP Meteor
		\add_filter('wpmeteor_enabled', '__return_false');

		\add_action(
			'wp_print_scripts',
			function (): void {
				$dist = MOTIONPAGE_DIR_URL . 'dist/';
				echo <<<HTML
				<script>
					window.__webpack_public_path__ = "{$dist}";
				</script>
				HTML;
			},
			0
		);

		Development::enqueue('embed', [
			'js' => true,
			'css' => true,
			'js_dep' => [
				'mp-MouseMove',
				'mp-ImageSequence',
				'mp-ScrollToPlugin',
				'mp-SplitText',
				'mp-DrawSVG',
				'mp-CustomEase',
				'mp-Flip',
				'mp-Observer',
				'mp-ScrollSmoother',
				'mp-MotionPathHelper',
				'mp-ScrambleText'
			],
			'css_dep' => [],
			'in_footer' => true,
			'media' => 'all',
			'dist_url' => $this->plugin->distURL(),
			'dist_path' => $this->plugin->distPath()
		]);
	}
}
