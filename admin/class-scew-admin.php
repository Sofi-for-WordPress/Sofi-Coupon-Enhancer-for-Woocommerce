<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://junaidbinjaman.com
 * @since      1.0.0
 *
 * @package    Scew
 * @subpackage Scew/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name and version number.
 * Defines a custom meta box in shop coupon edit page.
 * Defines a custom column into shop order listing table
 *
 * @package    Scew
 * @subpackage Scew/admin
 * @author     Junaid Bin Jaman <me@junaidbinjaman.com>
 */
class Scew_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Add a custom meta box for Shop Coupons.
	 *
	 * This function is responsible for adding a custom meta box to the WordPress admin interface
	 * on the Shop Coupon edit screen. The meta box displays a list of orders that were placed
	 * using the current coupon.
	 *
	 * @since 1.0.0
	 */
	public function scew_meta_boxes() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/class-scew-order-list.php';

		$order_list = new Scew_Order_List();

		add_meta_box( 'scew_order_list', __( 'Order List', 'scew' ), array( $order_list, 'order_list' ), 'shop_coupon' );
	}

	/**
	 * Adds a new column to the order list table.
	 *
	 * This function extends the table columns in the order list to include a new column
	 * that displays the used coupon for each order.
	 *
	 * @param array $columns Existing table columns.
	 * @return array The modified table columns with the new 'Used Coupon' column.
	 */
	public function custom_order_column( $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $value ) {
			if ( 'order_status' === $key ) {
				$new_columns['used_coupon'] = 'Used Coupon';
			}
			$new_columns[ $key ] = $value;
		}

		return $new_columns;
	}

	/**
	 * Display coupon data in the custom 'Used Coupon' column.
	 *
	 * This function is responsible for populating the custom 'Used Coupon' column
	 * in the order list with the coupon codes used for each order.
	 *
	 * @param string $column The column key being processed.
	 * @return void
	 */
	public function custom_order_column_content( $column ) {
		global $post;

		if ( 'used_coupon' === $column ) {
			$order   = wc_get_order( $post->ID );
			$coupons = $order->get_coupon_codes();
			echo esc_html( strtoupper( implode( ', ', $coupons ) ) );
		}
	}
}
