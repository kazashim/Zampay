<?php 

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

    }
    

}


?>