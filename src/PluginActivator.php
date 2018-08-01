<?php
/**
 * Define child theme constants.
 *
 * @package   SEOThemes\Core
 * @since     0.1.0
 * @link      https://github.com/seothemes/core-plugin-activator
 * @author    SEO Themes
 * @copyright Copyright © 2018 SEO Themes
 * @license   GPL-2.0+
 */

namespace SEOThemes\Core;

use D2\Core\Core;

/**
 * Add recommended plugins to child theme.
 *
 * Example config (usually located at config/defaults.php):
 *
 * ```
 * use SEOThemes\Core\PluginActivator;
 *
 * $plugins = [
 *     PluginActivator::REGISTER => [
 *         'Genesis eNews Extended',
 *         'Genesis Simple FAQ',
 *         'Genesis Testimonial Slider',
 *         'Genesis Widget Column Classes',
 *         'Google Map',
 *         'Icon Widget',
 *         'One Click Demo Import',
 *         'Simple Social Icons',
 *         'WP Featherlight',
 *     ],
 * ];
 *
 * return [
 *     PluginActivator::class => $plugins,
 * ];
 * ```
 */
class PluginActivator extends Core {

	const REGISTER = 'register';

	public $plugins = [];

	/**
	 * Initialize class.
	 *
	 * @since  0.1.0
	 *
	 * @return void
	 */
	public function init() {

		if ( class_exists( 'TGM_Plugin_Activation' ) && array_key_exists( self::REGISTER, $this->config ) ) {

			new \TGM_Plugin_Activation();

			$this->register_plugins();

		}

	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @param $config
	 *
	 * @return void
	 */
	public function create_plugin_array() {

		foreach ( $this->config[ self::REGISTER ] as $plugin ) {

			$this->plugins[] = [
				'name'     => $plugin,
				'slug'     => strtolower( str_replace( ' ', '-', $plugin ) ),
				'required' => false,
			];

		}

	}

	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function require_woocommerce_connect() {

		if ( class_exists( 'WooCommerce' ) ) {

			$this->plugins[] = [
				'name'     => 'Genesis Connect WooCommerce',
				'slug'     => 'genesis-connect-woocommerce',
				'required' => true,
			];

		}

	}


	/**
	 * Description of expected behavior.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function tgmpa_settings() {

		return [
			'id'           => get_stylesheet(),
			'default_path' => '',
			'menu'         => 'tgmpa-install-plugins',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => false,
			'message'      => '',
		];

	}

	/**
	 * Register required plugins.
	 *
	 * The variables passed to the `is_tgmpa_active()` function should be:
	 *
	 * - an array of plugin arrays;
	 * - optionally a configuration array.
	 *
	 * If you are not changing anything in the configuration array, you can
	 * remove the array and remove the variable from the function call: `tgmpa(
	 * $plugins );`. In that case, the TGMPA default settings will be used.
	 *
	 * This function is hooked into `tgmpa_register`, which is fired on the WP
	 * `init` action on priority 10.
	 *
	 * @since  0.1.0
	 *
	 * @return void
	 */
	public function register_plugins() {

		$this->create_plugin_array();
		$this->require_woocommerce_connect();

		\tgmpa( $this->plugins, $this->tgmpa_settings() );

	}

}
