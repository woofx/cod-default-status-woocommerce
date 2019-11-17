<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://woofx.kaizenflow.xyz
 * @since      1.0.0
 *
 * @package    Cod_Default_Status_For_Woocommerce
 * @subpackage Cod_Default_Status_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cod_Default_Status_For_Woocommerce
 * @subpackage Cod_Default_Status_For_Woocommerce/admin
 * @author     WooFx <rafaat.ahmed@kaizenflow.xyz>
 */
class Cod_Default_Status_For_Woocommerce_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Add fields to the payment method settings page. Called from settings render hook.
	 *
	 * @since    1.0.0
	 */
	public function add_fields() {
		global $current_section;

		$payment_gateways = WC()->payment_gateways->payment_gateways();
		
		if ( $current_section == 'cod' ){
			echo $this->generate_settings_html( $payment_gateways['cod'] );
		}
	}

	/**
	 * Generate settings HTML for conditions
	 *
	 * @since    1.0.0
	 * @param	WC_Payment_Gateway	$gateway	WooCommerce payment gateway object
	 * @return  string							Admin options html
	 */
	public function generate_settings_html( $gateway ) {

		// load options
		$plugin = Cod_Default_Status_For_Woocommerce::instance();
		$_option_name_status = $plugin->get_option_name('order_status');
		$_option_name_inventory = $plugin->get_option_name('reduce_stock');
		$_option_status = get_option($_option_name_status,'wc-processing');
		$_option_inventory = get_option($_option_name_inventory,0);
		
		// status html
		$statuses = wc_get_order_statuses();
		

		ob_start();

		include(plugin_dir_path( __FILE__ ) . 'partials/cod-default-status-for-woocommerce-admin-display.php');

		return ob_get_clean();
	}

	/**
	 * COD Settings update process callback
	 *
	 * @since    1.0.0
	 */
	public function process_options() {

		// load options
		$plugin = Cod_Default_Status_For_Woocommerce::instance();
		$_option_name_status = $plugin->get_option_name('order_status');
		$_option_name_inventory = $plugin->get_option_name('reduce_stock');
		
		if(isset($_POST[$_option_name_status])){
			update_option($_option_name_status,$_POST[$_option_name_status]);
		}

		if(isset($_POST[$_option_name_inventory])){
			update_option($_option_name_inventory,1);
		}
		else update_option($_option_name_inventory,0);
	}

	/**
	 * Set status for orders made with COD
	 *
	 * @since    1.0.0
	 * @param	string		$status	Default WC_Order status.
	 * @param	WC_Order	$order	WC_Order object.
	 * @return  string				WC_Order status.
	 */
	function set_cod_process_payment_order_status( $status, $order ) {
		
		// load options
		$plugin = Cod_Default_Status_For_Woocommerce::instance();
		$default_status = $plugin->get_option('order_status','wc-processing');

		// remove wc- prefix
		$default_status = str_replace('wc-','',$default_status);

		return $default_status;

	}

	/**
	 * Inventory reduction filter hook during checkout process
	 *
	 * @since    1.0.0
	 * @param	bool		$reduce_stock	Default reduce stock option.
	 * @param	WC_Order	$order			WC_Order object.
	 * @return  bool						Reduce stock value.
	 */
	function do_not_reduce_stock($should_reduce,$order_id){

		// Getting an instance of the order object
		$order = wc_get_order( $order_id );

		// Load options
		$plugin = Cod_Default_Status_For_Woocommerce::instance();
		$reduce_stock = $plugin->get_option('reduce_stock',1);
		$default_status = $plugin->get_option('order_status','wc-processing');

		//remove wc- prefix
		$default_status = str_replace('wc-','',$default_status);

		// return default if default_status == 'processing
		if( 'processing' == $default_status ) return $should_reduce;

		// Do not reduce stock if conditions met
		if ( 1 == $reduce_stock && 
			'cod' == $order->get_payment_method() ) {

				$should_reduce = false;

		}

		return $should_reduce;
	}

	/**
	 * Inventory event during order status change
	 *
	 * @since    1.0.0
	 * @param	string		$order_id	Order id.
	 * @param	string		$old_status	Old WC_Order status.
	 * @param	string		$new_status	New WC_Order status.
	 * @param	WC_Order	$order		WC_Order object.
	 */
	function order_stock_reduction_based_on_status( $order_id, $old_status, $new_status, $order ){
		
		// load options
		$plugin = Cod_Default_Status_For_Woocommerce::instance();
		$default_status = $plugin->get_option('order_status','wc-processing');
		$reduce_stock = $plugin->get_option('reduce_stock',1);
		
		//remove wc- prefix
		$default_status = str_replace('wc-','',$default_status);
		
		// do nothing if default is processing
		if( 'processing' == $default_status ) return;
		
		if( $new_status == 'processing' && $old_status == $default_status){
			if( $order->get_payment_method() == 'cod' && $reduce_stock == 1 ){
				wc_reduce_stock_levels($order_id);
			}

		}
		if( $new_status == $default_status && $old_status == 'processing'){
			if( $order->get_payment_method() == 'cod' && $reduce_stock == 1 ){
				wc_increase_stock_levels($order_id);
			}
		}
		
	}

}
