<?php 
namespace ZAMPAY;

require_once( MOMOPAY_PLUGIN_DIR_PATH . 'zampay-sdk/vendor/autoload.php' );


use GuzzleHttp\Client;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class zampay

{
    protected $ThirdPartyID;
    protected $Password;
    protected $Msisdn;
    protected $Shortcode;
    protected $Trasactionid;
    protected $Amount;
    protected $base_url;


    public function __construct($ThirdPartyID, $Password, $Msisdn, $Shortcode )

    {
        
        $this->ThirdPartyID = $ThirdPartyID;
        $this->Password = $Password;
        $this->Msisdn =  $Msisdn;
        $this->Shortcode = $Shortcode;

        // create a log channel
        $log = new Logger('zamtel/momopay');
        $this->logger = $log;
        $log->pushHandler(new RotatingFileHandler('momopay.log', 90, Logger::DEBUG));

        // logs
        $this->logger->notice('Momopay Class Initializes....');

        $this->setEventHandler($event_handler);
        $this->setAccessToken();

        return $this;
    }
    

    public function setAmount($Amount)
    {
        $this->Amount = $Amount;
        return $this;
    }

    public function setMsisdn($Msisdn)
    {
        $this->Msisdn = $Msisdn;
        return $this;
    }

    public function setBody()
    {
        $data =  array(
            'amount' => $this->Amount, 
            'externalId' => $this->external_id,
            'payer' => array(
                'partyIdType' => 'MSISDN',
                'partyId' => $this->phone
            ),
        );
        $this->body = json_encode($data);
    }
}



?>