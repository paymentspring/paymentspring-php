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
  }
