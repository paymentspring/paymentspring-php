<?php
  namespace PaymentSpring;
  
  class Plan {

    /*
      *
      * List all plans
      *
      * @return Plan JSON Object 
    */

    public static function listPlans(){
      $response = PaymentSpring::makeRequest("plans");
      return json_decode($response);
    } 

    /*
      *
      * Subscribe a customer to a plan 
      *
      * @param $planId integer
      * @param $customerId integer
      * @param $options array optional 
      * @return Subscription JSON Object 
    */

    public static function subscribeCustomer($planId, $customerId, $options = array()){
      $requestPath = "plans/$planId/subscription/$customerId";
      $response = PaymentSpring::makeRequest($requestPath, $options, true);
      return json_decode($response);
    }

    /*
      *
      * Create and subscribe a customer to a plan 
      *
      * @param $customerDetails array 
      * @param $planId integer
      * @param $options array optional 
      * @return Customer JSON Object
    */

    public static function createAndSubscribeCustomer($customerDetails, $planId, $options = array()){
      $customer = \PaymentSpring\Customer::createCustomer($customerDetails); 
      if($customer && isset($customer->id)){
        return self::subscribeCustomer($planId, $customer->id, $options);
      }else{
        return json_decode($customer);
      }
    }
  }
