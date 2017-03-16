<?php
  namespace PaymentSpring;

  class Customer{

    public static function listCustomers(){
      $response = PaymentSpring::makeRequest("customers");
      return json_decode($response);
    }

    public static function createCustomer($customerDetails){
      $response = PaymentSpring::makeRequest("customers", $customerDetails, true);
      return json_decode($response);
    }

  }
  
