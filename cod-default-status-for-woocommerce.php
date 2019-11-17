<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://woofx.kaizenflow.xyz
 * @since             1.0.0
 * @package           Cod_Default_Status_for_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       COD Default Status for WooCommerce
 * Plugin URI:        https://github.com/woofx/cod-default-status-woocommerce
 * Description:       Set default status for Cash on Delivery (COD) orders. Also manage inventory reduction behavior for COD orders.
 * Version:           1.0.1
 * Author:            WooFx
 * Author URI:        https://woofx.kaizenflow.xyz
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cod-default-status-woocommerce
 * Domain Path:       /languages
 * WC requires at least: 3.0.0
 * WC tested up to: 3.8.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'COD_DEFAULT_STATUS_FOR_WOOCOMMERCE_VERSION', '1.0.0' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cod-default-status-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cod_default_status_for_woocommerce() {

	$plugin = Cod_Default_Status_For_Woocommerce::instance();
	$plugin->run();

}
run_cod_default_status_for_woocommerce();
