<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://woofx.kaizenflow.xyz
 * @since      1.0.0
 *
 * @package    Cod_Default_Status_For_Woocommerce
 * @subpackage Cod_Default_Status_For_Woocommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Cod_Default_Status_For_Woocommerce
 * @subpackage Cod_Default_Status_For_Woocommerce/includes
 * @author     WooFx <rafaat.ahmed@kaizenflow.xyz>
 */
class Cod_Default_Status_For_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Cod_Default_Status_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * Reference to the activated plugin instance.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Cod_Default_Status_For_Woocommerce    $_instance    Reference to the activated plugin instance.
	 */
	protected static $_instance = null;

	/**
	 * Main Plugin Instance
	 *
	 * Ensures only one instance of plugin is loaded or can be loaded.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'COD_DEFAULT_STATUS_FOR_WOOCOMMERCE_VERSION' ) ) {
			$this->version = COD_DEFAULT_STATUS_FOR_WOOCOMMERCE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'cod-default-status-for-woocommerce';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Cod_Default_Status_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Cod_Default_Status_For_Woocommerce_i18n. Defines internationalization functionality.
	 * - Cod_Default_Status_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cod-default-status-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cod-default-status-for-woocommerce-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cod-default-status-for-woocommerce-admin.php';

		$this->loader = new Cod_Default_Status_For_Woocommerce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Cod_Default_Status_For_Woocommerce_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Cod_Default_Status_For_Woocommerce_i18n();

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

		$plugin_admin = new Cod_Default_Status_For_Woocommerce_Admin( $this->get_plugin_name(), $this->get_version() );

		// Display options for cod payment method
		$this->loader->add_action( 'woocommerce_settings_checkout', $plugin_admin, 'add_fields' , 20, 0 );
		
		// Process options for cod payment method
		$this->loader->add_action( 'woocommerce_update_options_payment_gateways_cod', $plugin_admin, 'process_options' , 10, 0 );

		// Set order status, during payment process stage
		$this->loader->add_filter( 'woocommerce_cod_process_payment_order_status',  $plugin_admin, 'set_cod_process_payment_order_status', 10, 2 );
		
		// Reduce stock hook for COD orders
		$this->loader->add_filter( 'woocommerce_payment_complete_reduce_order_stock', $plugin_admin,  'do_not_reduce_stock', 10, 2 );

		// Change inventory on status change
		$this->loader->add_action( 'woocommerce_order_status_changed', $plugin_admin,  'order_stock_reduction_based_on_status', 20, 4 );		

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
	 * @return    Cod_Default_Status_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
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

	/**
	 * The name of the option key prefixed with plugin name, used to uniquely 
	 * identify it within the context of WordPress.
	 *
	 * @since	1.0.0
	 * @param	string	$key	Option key name.
	 * @return  string			Prefixed name of option.
	 */
	public function get_option_name($key) {
		return $this->plugin_name . '-' . $key;
	}

	/**
	 * The value of the option by using plugin local option key (not prefixed).
	 *
	 * @since	1.0.0
	 * @param	string	$key		Option key name
	 * @param	mixed	$default	Default option value
	 * @return  mixed				Option value.
	 */
	public function get_option($key,$default) {
		$_name = $this->plugin_name . '-' . $key;
		return get_option($_name,$default);
	}

}
