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
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
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
	 * Custom meta boxes
	 *
	 * The function bellow add custom meta boxes to different post type.
	 *
	 * @return void
	 */
	public function scew_meta_boxes() {
		/**
		 * Shop coupon meta box.
		 *
		 * The meta box is attached with shop coupon page.
		 * It list orders which are placed using current coupon.
		 */

		require_once plugin_dir_path( __FILE__ ) . 'partials/class-scew-order-list.php';

		$order_list = new Scew_Order_List();

		add_meta_box( 'scew_order_list', __( 'Order List', 'scew' ), array( $order_list, 'order_list' ), 'shop_coupon' );
	}

	/**
	 * Adds a new column into order list
	 *
	 * The new column displays the used coupon.
	 *
	 * @param array $columns table columns.
	 * @return array
	 */
	public function custom_order_column( $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $value ) {
			if ( 'order_total' === $key ) {
				$new_columns['used_coupon'] = 'Used Coupon';
			}
			$new_columns[ $key ] = $value;
		}
		return $new_columns;
	}

	/**
	 * Display coupon data into the new column.
	 *
	 * @param array $column column keys.
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
