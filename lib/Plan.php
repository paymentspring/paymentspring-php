<?php
  namespace PaymentSpring;
  
  class Plan {
    public static function listPlans(){
      return PaymentSpring::makeRequest("plans", array());
    } 
  }
