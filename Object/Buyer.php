<?php

class Vendor_DineroMail_Object_Buyer extends Vendor_DineroMail_Object_Object {

	protected $_address = '';
	protected $_city = '';
	protected $_country = '';
	protected $_email = '';
	protected $_lastname = '';
	protected $_name = '';
	protected $_phone = '';
	
	protected $_gateway = null;
	
	public function __construct(Vendor_DineroMail_Gateway_Abstract $gateway) {
		$this->_gateway = $gateway;
	}
	
	public function setAddress($address) {
		$this->_address = $address;
	}
	
	public function setCity($city) {
		$this->_address = $city;
	}
	
	public function setCountry($country) {
		$this->_country = $country;
	}
	
	public function setEmail($email) {
		$this->_email = $email;
	}
	
	public function setLastName($lastname) {
		$this->_lastname = $lastname;
	}
	
	public function setName($name) {
		$this->_name = $name;
	}
	
	public function setPhone($phone) {
		$this->_phone = $phone;
	}	
	
	
	public function asSoapObject() {
		return new SOAPVar(array('Address' => $this->_address
									,'City' => $this->_city
									,'Country' => $this->_country
									,'Email' => $this->_email
									,'LastName' => $this->_lastname
									,'Name' => $this->_name
									,'Phone' => $this->_phone)
									, SOAP_ENC_OBJECT, 'Buyer', $this->_gateway->getNameSpace());
	}
	
	public function __toString() {
		
		return $this->_name . $this->_lastname . $this->_email . $this->_address . $this->_phone . $this->_country . $this->_city;		
	}

}
