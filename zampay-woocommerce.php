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
