<?php

abstract class Vendor_DineroMail_Object_Object {

    protected $_gateway = null;


    public final function __construct(Vendor_DineroMail_Gateway_Abstract $gateway) {
        $this->_gateway = $gateway;
    }

    public function getGateway() {
        return $this->_gateway;
    }

    /**
     * Represents and object as SOAPVar
     *
     * @return SOAPVar the SOAPVar object containing all the required data
     */
    public abstract function asSoapObject();

    
}