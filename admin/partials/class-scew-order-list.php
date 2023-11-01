<?php
/**
 * Order List Meta Box
 *
 * This file is responsible for displaying a sorted list of orders on the shop coupon page.
 *
 * @link       https://junaidbinjaman.com
 * @since      1.0.0
 *
 * @package    Scew
 * @subpackage Scew/admin/partials
 */

/**
 * Scew Order List Class
 *
 * This class handles the display of sorted orders.
 */
class Scew_Order_List {
	/**
	 * Display the sorted list of orders.
	 *
	 * @return void
	 */
	public function order_list() {
		// Require the table file.
		require_once __DIR__ . '/class-scew-order-list-table.php';
		$order_list_table = new Scew_Order_List_Table();

		// Prepare the items for display.
		$order_list_table->prepare_items();

		// Display the order list table.
		echo '<div class="wrap">';
		$order_list_table->display();
		echo '</div>';
		?>
		<?php
	}
}
