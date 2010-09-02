<?php

class Vendor_DineroMail_Service {

	const PROVIDER_RAPIPAGO 	= "rapipago";
	const PROVIDER_PAGOFACIL 	= "pagofacil";
	const PROVIDER_BAPRO 		= "bapro";
	const PROVIDER_COBROEXPRESS = "cobroexpress";

	const CURRENCY_PESO_ARG		= "ARS";
	const CURRENCY_REAL			= "BRL";
	const CURRENCY_PESO_MEX		= "MXN";
	const CURRENCY_PESO_CHI		= "CLP";
	const CURRENCY_USD			= "USD";


	const RESULT_COMPLETED		= "COMPLETED";
	const RESULT_ERROR			= "ERROR";


	protected $_currency 	= self::CURRENCY_PESO_ARG;
	protected $_provier		= self::PROVIDER_PAGOFACIL;

	protected $_connection	= null;
	protected $_client		= null;


	public function __construct(Vendor_DineroMail_Connection $connection) {

		$this->_connection	= $connection;
		$this->setupClient();

	}

	public function getConnection() {
		return $this->_connection;
	}

	public function setCurrency($cur) {
		$this->_currency = $cur;
	}

	public function getCurrency() {
		return $this->_currency;
	}


	public function getProvier() {
		return $this->_provier;
	}



	protected function setupClient() {

		$this->_client = new SoapClient($this->getConnection()->getGateway()->getWdsl(), array('trace' => 1,'exceptions' => 1)); 	
	}
	
	protected function getClient() {
		return $this->_client;
	}

	protected function credentialsObject() {
		
		return new SOAPVar(array('APIUserName' => $this->getConnection()->getCredentials()->getUserName(),
			'APIPassword'=> $this->getConnection()->getCredentials()->getPassword()),
			SOAP_ENC_OBJECT,
			'APICredential',
			$this->getConnection()->getGateway()->getNameSpace());
	}



	protected function call($function, $parameters) {

		try {
			$response = $this->getClient()->$function($parameters);
			return $response;
		}
		catch (SoapFault $ex) {
			throw new Vendor_DineroMail_Exception($ex->getMessage(), $ex->getCode());
		}
	}


	public function doPaymentWithReference(array $items, Vendor_DineroMail_Object_Buyer $buyer, $transactionId) {
	
	
		$messageId = $this->uniqueId();
		$itemsChain = '';
		$oitems = array();
		
		foreach($items as $item) {
			$itemsChain .= $item;
			$oitems[] = $item->asSoapObject();
		}
		
		$hash = $this->hash($transactionId, $messageId, $itemsChain, $buyer, $this->getProvier(), $this->getConnection()->getCredentials()->getPassword());


		

		$request = array('Credential' => $this->credentialsObject()
			,'Crypt' =>  false
			,'MerchantTransactionId' => $transactionId
			,'UniqueMessageId' => $messageId
			,'Provider' => $this->getProvier()
			,'Message' => ""
			,'Subject' => ""
			,'Items'=> $oitems
			,'Buyer'=> $buyer->asSoapObject()
			,'Hash' => $hash);	

		$result = $this->call("DoPaymentWithReference", $request);

		return $result->DoPaymentWithReferenceResult;

	}





	public function getPaymentTicket() {


	}



	protected function uniqueId() {
		return time();
	}


	protected function hash(/* polimorphic */) {
		$args = func_get_args();
		return md5(implode("", $args));
	}




}



?>