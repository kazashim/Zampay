<?php
/**
 * Plugin Name: Zampay WooCommerce Payment Gateway
 * Description: WooCommerce payment gateway for Zampay
 * Author: kazashim kuzasuwat
 * Author URI: http://
 * Version: 1.0.0
 * License: GNU GPLv3
 */

define( 'ZAMPAY_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

require_once( ZAMPAY_PLUGIN_DIR_PATH . 'zampay-sdk/lib/Zampaypay.php' );
require_once( ZAMPAY_PLUGIN_DIR_PATH . 'zampay-sdk/lib/EventHandlerInterface.php' );
require_once( ZAMPAY_PLUGIN_DIR_PATH . 'zampay-sdk/lib/ZampayEventHandler.php')
use Zamtel\ZampayEventHandler;
use Zamtel\Zampay;

/**
 * This action hook registers our PHP class as a WooCommerce payment gateway
 */
add_filter( 'woocommerce_payment_gateways', 'Zampaypay_add_gateway_class' );

function zampaypay_add_gateway_class($gateways ) {
    $gateways[] = 'WC_Zampay_Gateway';
    return $gateways;
}
/**
 * The class itself, please note that it is inside plugins_loaded action hook
 */
add_action( 'plugins_loaded', 'zampay_init_gateway_class' );

/**
 * Add the Settings link to the plugin
 *
 * @param  array $links Existing links on the plugin page
 *
 * @return array          Existing links with our settings link added
 */
function zampay_plugin_action_links( $links ) {

    $zampay_settings_url = esc_url( get_admin_url( null, 'admin.php?page=wc-settings&tab=checkout&section=zampay' ) );
    array_unshift( $links, "<a title='ZamPay Settings Page' href='$zampay_settings_url'>Settings</a>" );

    return $links;

}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'zampay_plugin_action_links' );

/**
 *
 */
function zampay_init_gateway_class() {

    if ( !class_exists( 'WC_Payment_Gateway' ) ) return;

    class WC_Momopay_Gateway extends WC_Payment_Gateway {

        /**
         * Class constructor, more about it in Step 3
         */
        public function __construct()
        {
            $this->id = 'zampay'; // payment gateway plugin ID
            $this->icon = plugins_url('assets/img/momopay.png', __FILE__);
            $this->has_fields = true; // in case you need a custom credit card form
            $this->method_title = __('ZamPay', 'zampay-payments');
            $this->method_description = __('ZamPay allows you to accept payment from Zamtel mobile subscribers .', 'zampay-payments'); // will be displayed on the options page

// gateways can support subscriptions, refunds, saved payment methods,
           
            $this->supports = array(
                'products'
            );

    // Method with all the options fields
    $this->init_form_fields();

    // Load the settings.
    $this->init_settings();

    $this->title = $this->get_option( 'title' );
    $this->description = $this->get_option( 'description' );
    $this->enabled = $this->get_option( 'enabled' );

    $this->ThirdPartyID   =  $this->go_live ? $this->get_option( 'ThirdPartyID' ) : $this->get_option( 'ThirdPartyID' );
    $this->Password   =  $this->go_live ? $this->get_option( 'Password' ) : $this->get_option( 'Password' );