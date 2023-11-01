<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across
 * Sofi Coupon Enhancer plugin
 *
 * @link       https://github.com/junaidbinjaman/Sofi-Coupon-Enhancer-for-Woocommerce/
 * @since      1.0.0
 *
 * @package    Scew
 * @subpackage Scew/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Scew
 * @subpackage Scew/includes
 * @author     Junaid Bin Jaman <me@junaidbinjaman.com>
 */
class Scew {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Scew_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SCEW_VERSION' ) ) {
			$this->version = SCEW_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'scew';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - TGM Activation Library: Activates the required plugins.
	 * - Scew_Loader. Orchestrates the hooks of the plugin.
	 * - Scew_i18n. Defines internationalization functionality.
	 * - Scew_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		* Include TGM Activation Library
		*
		* This code includes the TGM Activation Library, which is responsible for checking
		* if the required plugin (woocommerce) is activated or not.
		*/
		require_once plugin_dir_path( __DIR__ ) . 'includes/tgm-setup.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-scew-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-scew-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-scew-admin.php';

		$this->loader = new Scew_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Scew_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Scew_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Scew_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'scew_meta_boxes' );
		$this->loader->add_filter( 'manage_edit-shop_order_columns', $plugin_admin, 'custom_order_column' );
		$this->loader->add_action( 'manage_shop_order_posts_custom_column', $plugin_admin, 'custom_order_column_content' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Scew_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
