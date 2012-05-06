<?php

/**
 * Represents and contains all logic required to call the DineroMail
 * service
 *
 * @see Vendor_DineroMail_Connection
 */
class Vendor_DineroMail_Service {

    const PROVIDER_RAPIPAGO     = "rapipago";
    const PROVIDER_PAGOFACIL    = "pagofacil";
    const PROVIDER_BAPRO        = "bapro";
    const PROVIDER_COBROEXPRESS = "cobroexpress";

    const CURRENCY_PESO_ARG     = "ARS";
    const CURRENCY_REAL         = "BRL";
    const CURRENCY_PESO_MEX     = "MXN";
    const CURRENCY_PESO_CHI     = "CLP";
    const CURRENCY_USD          = "USD";

    const RESULT_COMPLETED      = "COMPLETED";
    const RESULT_ERROR          = "ERROR";

    protected $_currency        = self::CURRENCY_PESO_ARG;
    protected $_provider        = self::PROVIDER_PAGOFACIL;

    protected $_connection      = null;
    protected $_client          = null;


    public function __construct(Vendor_DineroMail_Connection $connection) {
        $this->_connection = $connection;
        $this->setupClient();
    }

    public function setConnection(Vendor_DineroMail_Connection $connection) {
        return $this->_connection = $connection;
    }

    public function getConnection() {
        return $this->_connection;
    }

    public function setCurrency($currency) {
        $this->_currency = $currency;
    }

    public function getCurrency() {
        return $this->_currency;
    }
    
    public function setProvider($provider) {
        return $this->_provider = $provider;
    }

    public function getProvider() {
        return $this->_provider;
    }

    protected function getClient() {
        return $this->_client;
    }

    /**
     * Setups the soap client object
     *
     * @return SoapClient the soap object
     */
    protected function setupClient() {
        $this->_client = new SoapClient($this->getConnection()->getGateway()->getWdsl(),
                                        array('trace' => 1,
                                              'exceptions' => 1));  
    }

    /**
     * Returns the soap credential object
     *
     * @return SOAPVar the soap object
     */
    protected function credentialsObject() {

        $connection = $this->getConnection();

        return new SOAPVar(array('APIUserName' => $connection->getCredentials()->getUserName(),
                                 'APIPassword'=> $connection->getCredentials()->getPassword()),
                           SOAP_ENC_OBJECT,
                           'APICredential',
                           $connection->getGateway()->getNameSpace());
    }

    /**
     * makes the raw call to the service using the SoapClient
     * @see Vendor_DineroMail_Exception
     *
     * @param $function string function to call
     * @param $parameters array contains the parameters to send to the webservice
     * @return stdClass raw webservice response
     * @throws Vendor_DineroMail_Exception in case some error
     */
    protected function call($function, array $parameters) {

        try {
            $response = $this->getClient()->$function($parameters);
            return $response;
        }
        catch (SoapFault $ex) {
            throw new Vendor_DineroMail_Exception($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * encapsulates the call to the DineroMail service invoking the method
     * doPaymentWithReference
     * @link https://api.dineromail.com/dmapi.asmx?WSDL
     *
     * @param array $items items to create the payment
     * @param Vendor_DineroMail_Object_Buyer $buyer contains the buyer information
     * @param string $transactionId an unique TX id
     */
    public function doPaymentWithReference(array $items, Vendor_DineroMail_Object_Buyer $buyer, $transactionId) {

        $messageId = $this->uniqueId();
        $itemsChain = '';
        $oitems = array();

        foreach($items as $item) {
            $itemsChain .= $item;
            $oitems[] = $item->asSoapObject();
        }

        $hash = $this->hash($transactionId,
                            $messageId,
                            $itemsChain,
                            $buyer,
                            $this->getProvider(),
                            $this->getConnection()->getCredentials()->getPassword());


        $request = array('Credential' => $this->credentialsObject(),
                         'Crypt' =>  false,
                         'MerchantTransactionId' => $transactionId,
                         'UniqueMessageId' => $messageId,
                         'Provider' => $this->getProvider(),
                         'Message' => '',
                         'Subject' => '',
                         'Items'=> $oitems,
                         'Buyer'=> $buyer->asSoapObject(),
                         'Hash' => $hash);  

        $result = $this->call("DoPaymentWithReference", $request);

        return $result->DoPaymentWithReferenceResult;

    }

    /**
     * Returns an unique id for each service call
     *
     * @param void
     * @return string al sinple call to the microtime function
     */
    protected function uniqueId() {

        return (string) microtime();
    }

    /**
     * Returns a md5 hash of all given parameters
     *
     * @param 1..n parameters to hash
     * @return string containing the md5
     */
    protected function hash(/* polimorphic */) {

        $args = func_get_args();
        return md5(implode("", $args));
    }

}