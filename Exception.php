<?php

class Vendor_DineroMail_Exception extends Exception {

    public function __construct($string, $code) {
        parent::__construct($string, $code);
    }

    public function __toString() {
        return sprintf("(%s) %s", $this->code, $this->message);
    }
    
}