<?php
/**
 * Created by Kazashim Kuzasuwat.
 */

namespace ZAMPAY;

//require_once( MOMOPAY_PLUGIN_DIR_PATH . 'zampay-sdk/vendor/autoload.php' );

class ZampayEventHandler implements EventHandlerInterface
{
    private $order;

    function __construct($order)
    {
        $this->order = $order;
    }

    function onInit()
    {
        $this->order->add_order_note('Payment initialized via Zampay');
    }

    function onAccessTokenFailure()
    {
        $this->order->add_order_note('Failed to fetch ZamPay API Access Token');
    }

    function onPaymentRequestInit($reference_id, $external_id)
    {
        $this->order->add_order_note('Payment Request initialized via Zampay');
        update_post_meta( $this->order->id, '_zampay_payment_reference_id', $reference_id );
        update_post_meta( $this->order->id, '_zampay_payment_external_id', $external_id );
        $this->order->add_order_note('Your transaction IDs :- Reference ID: '.$reference_id.', External ID: '.$external_id);
    }

    function onPaymentRequestFailure()
    {
        $this->order->add_order_note('Failed to send ZamPay payment request');
    }

    function onPaymentRequestSuccess()
    {
        $this->order->add_order_note('Successfully Sent ZamPay payment request');
    }

    public function onPaymentRequestStatusCheck($reference_id)
    {
        $this->order->add_order_note('Fetching Payment Request Status on ZamPay ref: '.$reference_id);
    }

    public function onPaymentRequestStatusCheckFailure($reference_id)
    {
        $this->order->add_order_note('Failed to fetch Request Status on ZamPay ref: '.$reference_id);
    }

    function onSuccessful($transaction_data, $env, $reference_id)
    {
        $body = json_decode($transaction_data);
        $currency = $env == 'sandbox' ? 'EUR' : $this->order->get_order_currency();
        if ($body->currency == $currency && $body->amount == round($this->order->order_total)){
            $this->order->payment_complete( $this->order->id );
            $this->order->add_order_note('Payment was successful on ZamPay');
            $this->order->add_order_note(' ZamPay transaction reference: '.$reference_id);

            $customer_note  = 'Thank you for your order.<br>';
            $customer_note .= 'Your payment was successful, we are now <strong>processing</strong> your order.';

            $this->order->add_order_note( $customer_note, 1 );
            wc_add_notice( $customer_note, 'notice' );
        }else{
            $this->order->update_status( 'on-hold' );
            $customer_note  = 'Thank you for your order.<br>';
            $customer_note .= 'Your payment successfully went through, but we have to put your order <strong>on-hold</strong> ';
            $customer_note .= 'because the we couldn\t verify your order. Please, contact us for information regarding this order.';
            $admin_note     = 'Attention: New order has been placed on hold because of incorrect payment amount or currency. Please, look into it. <br>';
            $admin_note    .= 'Amount paid: '. $body->currency.' '. $body->amount.' <br> Order amount: '.$this->order->get_order_currency().' '. $this->order->order_total.' <br> Reference: '.$reference_id;

            $this->order->add_order_note( $customer_note, 1 );
            $this->order->add_order_note( $admin_note );

            wc_add_notice( $customer_note, 'notice' );
        }

        wc_reduce_stock_levels($this->order->id);
        // Empty cart
        WC()->cart->empty_cart();
    }

    function onFailure($reference_id)
    {
        $this->order->update_status( 'Failed' );
        $this->order->add_order_note('Payment failed on ZamPay Reference ID: '.$reference_id);
    }

    function onRequery($reference_id)
    {
        $this->order->add_order_note('Requerying payment transaction on ZamPay Reference ID: '.$reference_id);
    }

    function onCancel()
    {
        $this->order->add_order_note('The customer Canceled The Payment request on their phone!');
        $this->order->update_status( 'Cancelled' );
        $admin_note     = 'Attention: Customer canceled the payment request on their phone. We have updated the order to canceled. <br>';
        $this->order->add_order_note( $admin_note );
    }

    function onTimeout($reference_id)
    {
        $this->order->add_order_note('The payment request timed out before being confirmed ');
        $this->order->update_status( 'on-hold' );
        $customer_note  = 'Please try again and confirm the payment request on your phone in time.';
        $admin_note    = 'Payment Reference: '.$reference_id;

        $this->order->add_order_note( $customer_note, 1 );
        $this->order->add_order_note( $admin_note );

        wc_add_notice( $customer_note, 'notice' );
    }

    function onReject($reference_id)
    {
        $this->order->add_order_note('The payment was rejected on ZamPay');
        $customer_note  = 'Your payment <strong>failed</strong>. ';
        $customer_note .= 'Please make sure you have enough funds on your mobile money account.';

        $this->order->add_order_note( $customer_note, 1 );

        wc_add_notice( $customer_note, 'notice' );
    }
}