<?php
  namespace PaymentSpring;

  class Customer{

    public static function createCustomer($customerDetails){
      return PaymentSpring::makeRequest("customers", $customerDetails, true);
    }

  }
  
