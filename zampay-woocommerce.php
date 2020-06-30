<?php
/**
 * Plugin Name: Zampay WooCommerce Payment Gateway
 * Description: WooCommerce payment gateway for Zampay
 * Author: kazashim kuzasuwat
 * Author URI: http://
 * Version: 1.0.0
 * License: GNU GPLv3
 */

define( 'MOMOPAY_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

require_once( MOMOPAY_PLUGIN_DIR_PATH . 'Zampay-sdk/lib/Momopay.php' );
require_once( MOMOPAY_PLUGIN_DIR_PATH . 'mtn-momopay-php-sdk/lib/EventHandlerInterface.php' );
require_once( MOMOPAY_PLUGIN_DIR_PATH . 'mtn-momopay-php-sdk/lib/MomopayEventHandler.php' );