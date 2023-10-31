<?php
/**
 * Order list table class
 *
 * This file is used to list sorted orders on table.
 *
 * @link       https://supporthost.com/wp-list-table-tutorial/
 * @since      1.0.0
 *
 * @package    Scew
 * @subpackage Scew/admin/partials
 */

/**
 * List sorted orders on WP Table
 */
class Scew_Order_List_Table extends WP_List_Table {

	/**
	 * Table data holder
	 *
	 * @var array
	 */
	private $table_data;

	/**
	 * Table column
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'name'   => __( 'Name', 'scew' ),
			'status' => __( 'Status', 'scew' ),
			'total'  => __( 'Total', 'scew' ),
			'date'   => __( 'Date', 'scew' ),
		);
		return $columns;
	}

	/**
	 * Prepare table items
	 *
	 * @return void
	 */
	public function prepare_items() {
		// data.
		$this->table_data = $this->get_table_data();

		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$primary               = 'name';
		$this->_column_headers = array( $columns, $hidden, $sortable, $primary );

		usort( $this->table_data, array( &$this, 'usort_reorder' ) );

		$this->items = $this->table_data;
	}

	/**
	 * Manage table data
	 *
	 * @return array
	 */
	private function get_table_data() {
		$sorted_orders = array();

		$orders = wc_get_orders(
			array(
				'limit' => -1,
			)
		);

		$coupon_id   = get_the_ID();
		$coupon_code = wc_get_coupon_code_by_id( $coupon_id );

		foreach ( $orders as $order ) {
			$codes = $order->get_coupon_codes();
			foreach ( $codes as $code ) {
				if ( strtolower( $coupon_code ) === $code ) {
					array_push( $sorted_orders, $order );
				}
			}
		}

		return $sorted_orders;
	}

	/**
	 * Show data in the column
	 *
	 * @param object $item orders data.
	 * @param string $column_name column name.
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'name':
				$first_name = $item->get_billing_first_name();
				$last_name  = $item->get_billing_last_name();
				return $first_name . ' ' . $last_name;
			case 'status':
				return ucwords( $item->get_status() );
			case 'total':
				return sprintf( '$%s', $item->get_total() );
			case 'date':
				$date = date_create( $item->get_date_created() );
				$date = date_format( $date, 'M d, Y h:i a' );
				return $date;
			default:
				return $item[ $column_name ];
		}
	}

	/**
	 * Display the table navigation.
	 *
	 * @param string $which sdfsdf.
	 */
	protected function display_tablenav( $which ) {
		if ( 'top' === $which ) {
			printf( 'Total number of orders %s', count( $this->table_data ) );
			$this->fix_usage_count();
		}
		?>
		<?php
	}

	/**
	 * Fix wrong usage count
	 *
	 * The function compare number of elements in the row with
	 * usage count saved in the database. If doesn't match, it updates the usage count with elements count
	 *
	 * @return void
	 */
	private function fix_usage_count() {
		$usage_count = get_post_meta( get_the_ID(), 'usage_count', true );
		$order_count = count( $this->table_data );

		if ( $usage_count !== $order_count ) {
			update_post_meta( get_the_ID(), 'usage_count', $order_count );
		}
	}
}
