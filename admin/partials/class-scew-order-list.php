<?php
/**
 * Order list meta box
 *
 * This file is used to list sorted orders on shop coupon page.
 *
 * @link       https://junaidbinjaman.com
 * @since      1.0.0
 *
 * @package    Scew
 * @subpackage Scew/admin/partials
 */

/**
 * List out all the sorted orders
 */
class Scew_Order_List {
	/**
	 * List out all the sorted orders.
	 *
	 * @return void
	 */
	public function order_list() {
		// Require the table file.
		require_once __DIR__ . '/class-scew-order-list-table.php';
		$order_list_table = new Scew_Order_List_Table();

		$order_list_table->prepare_items();
		echo '<div class="wrap">';
		$order_list_table->display();
		echo '</div>';
		?>
		<?php
	}
}
