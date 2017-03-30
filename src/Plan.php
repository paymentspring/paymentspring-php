<?php
  namespace PaymentSpring;
  
  class Plan {
    public static function listPlans(){
      $response = PaymentSpring::makeRequest("plans");
      return json_decode($response);
    } 

    public static function subscribeCustomer($planId, $customerId, $options){
      $requestPath = "plans/$planId/subscription/$customerId";
      $response = PaymentSpring::makeRequest($requestPath, $options, true);
      return json_decode($response);
    }

    public static function createAndSubscribeCustomer($customerDetails, $planId, $options){
      $customer = \PaymentSpring\Customer::createCustomer($customerDetails); 
      if($customer && isset($customer->id)){
        return self::subscribeCustomer($planId, $customer->id, $options);
      }else{
        return json_decode($customer);
      }
    }
  }
