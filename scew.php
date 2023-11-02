<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://junaidbinjaman.com
 * @since             1.0.0
 * @package           Scew
 *
 * @wordpress-plugin
 * Plugin Name:       Sofi Coupon Enhancer for Woocommerce
 * Plugin URI:        https://github.com/junaidbinjaman/sofi-coupon-enhancer-wocommerce
 * Description:       Enhance coupon code management in WooCommerce with the Sofi Coupon Enhancer. Display coupon orders, correct duplicated coupon counts, and add a coupon column to the order listing table.
 * Version:           1.0.0
 * Author:            Junaid Bin Jaman
 * Author URI:        https://junaidbinjaman.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       scew
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'SCEW_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-scew-activator.php
 */
function activate_scew() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-scew-activator.php';
	Scew_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-scew-deactivator.php
 */
function deactivate_scew() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-scew-deactivator.php';
	Scew_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_scew' );
register_deactivation_hook( __FILE__, 'deactivate_scew' );

/**
 * HPOS checker
 *
 * The function checks whether the website is using hpos or not
 *
 * @return void
 */
function scew_hpos_checker() {
	if ( class_exists( 'Automattic\WooCommerce\Utilities\OrderUtil' ) ) {
		if ( Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled() ) {
			update_option( 'is_hpos_enabled', true );
		} else {
			// Traditional CPT-based orders are in use.
			update_option( 'is_hpos_enabled', false );
		}
	}
}

add_action( 'plugins_loaded', 'scew_hpos_checker' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-scew.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_scew() {

	$plugin = new Scew();
	$plugin->run();
}
run_scew();
