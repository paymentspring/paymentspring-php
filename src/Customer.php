<?php
  namespace PaymentSpring;

  class Customer{

    /*
      *
      * Get all registered customers 
      *
      * @return JSON Object 
    */

    public static function listCustomers(){
      $response = PaymentSpring::makeRequest("customers");
      return json_decode($response);
    }

    /*
      *
      * Get a specific customer 
      *
      * @param $customerID integer
      * @return JSON Object 
    */

    public static function getCustomer($customerId){
      $response = PaymentSpring::makeRequest("customers/$customerId");
      return json_decode($response);
    }

    /*
      *
      * Create a customer 
      *
      * @param $customerDetails array
      * @return JSON Object 
    */

    public static function createCustomer($customerDetails){
      $response = PaymentSpring::makeRequest("customers", $customerDetails, true);
      return json_decode($response);
    }

  }
  
