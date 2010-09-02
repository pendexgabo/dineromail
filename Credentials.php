<?php

class Vendor_DineroMail_Credentials {
	
	
	protected $APIUserName 	= '';
	protected $APIPassword 	= '';
	
	
	public function __construct($APIUserName, $APIPassword) {
	
	$this->APIUserName 	= $APIUserName;
	$this->APIPassword 	= $APIPassword;

	}
	
	public function getUserName() {
		return $this->APIUserName;
	}
	
	public function getPassword() {
		return $this->APIPassword;
	}
	
	

}
