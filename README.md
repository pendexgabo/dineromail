# DineroMailService

The idea is to provide a basic wrapper on top of the [DineroMail API][1]

## Requirements:

 1. PHP 5.2.x
 2. SOAP support (current built in for PHP > 5.x)


## Current work

At the moment I only implemented the method **doPaymentWithReference**. But stay tuned!!! more changes coming soon.

## Example

This is a basic example to generate a payment coupon

    <?php
    
    $credentials = new Vendor_DineroMail_Credentials($apikey, $secret);
    $gateway = new Vendor_DineroMail_Gateway_Production();
    $connection = new Vendor_DineroMail_Connection($credentials, $gateway);
    $service = new Vendor_DineroMail_Service($connection);
    
    $item = new Vendor_DineroMail_Object_Item($gateway);
 
    $item->setAmount($amount);
    $item->setCode("code");
    $item->setDescription("Description");
    $item->setName("Name");
    
    $buyer = new Vendor_DineroMail_Object_Buyer($gateway);
    
    // $buyerObject contains the buyer information
    $buyer->setEmail($buyerObject->get("email"));
    $buyer->setaddress($buyerObject->get("address"));
    $buyer->setName($buyerObject->get("name"));
    $buyer->setLastName($buyerObject->get("last_name"));
    $buyer->setAddress($buyerObject->get("address"));
    
    
    try {
        // call the service
    	$ticketResult = $service->doPaymentWithReference(array($item), $buyer, $transactionId);
    }
    catch(Vendor_DineroMail_Exception $e) {
    	// drive the exception
    }




## License

Copyright (c) 2012 Gabriel Sosa

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE. 

  [1]: https://ar.dineromail.com/content/API.zip