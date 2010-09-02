<?php

class Vendor_DineroMail_Object_Item extends Vendor_DineroMail_Object_Object{
	
	protected $_amount = '';
	protected $_code = '';
	protected $_currency = Vendor_DineroMail_Service::CURRENCY_PESO_ARG;
	protected $_description = '';
	protected $_name = '';
	protected $_quantity = 1;
	
	protected $_gateway = null;
	
	public function __construct(Vendor_DineroMail_Gateway_Abstract $gateway) {
		$this->_gateway = $gateway;
	}
	
	public function setAmount($amount) {
		$this->_amount = strval($amount);
	}
	
	public function setCode($code) {
		$this->_code = $code;
	}
	
	public function setCurrency($currency) {
		$this->_currency = $currency;
	}
	
	public function setDescription($description) {
		$this->_description = $description;
	}
	
	public function setName($name) {
		$this->_name = $name;
	}

	public function setQuantity($quantity) {
		$this->_quantity = $quantity;
	}

	
	public function asSoapObject() {
		return new SOAPVar(array('Amount' => $this->_amount
			,'Code' => $this->_code
			,'Currency' => $this->_currency
			,'Description' => $this->_description
			,'Name' => $this->_name
			,'Quantity' => $this->_quantity)
			, SOAP_ENC_OBJECT, 'Item', $this->_gateway->getNameSpace());
	}

	public function __toString() {
		return $this->_amount . $this->_code . $this->_currency . $this->_description . $this->_name . $this->_quantity;
	}

}
