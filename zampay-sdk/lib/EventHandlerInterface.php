<?php
/**

 */

namespace ZAMPAY;


interface EventHandlerInterface
{
    function onInit();

    function onAccessTokenFailure();

    function onPaymentRequestInit($trasactionid, $external_id);

    function onPaymentRequestFailure();

    function onPaymentRequestSuccess();

    function onPaymentRequestStatusCheck($trasactionid);

    function onPaymentRequestStatusCheckFailure($trasactionid);

    function onSuccessful($transaction_data, $trasactionid);

    function onFailure($trasactionid);

    function onRequery($trasactionid);

    function onCancel();

    function onTimeout($trasactionid);

    function onReject($trasactionid);
}