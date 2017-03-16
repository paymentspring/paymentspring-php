<?php
  namespace PaymentSpring;

  class Charge{
    public static function chargeCard($cardDetails, $amount = null){
      if($amount){
        $cardDetails["amount"] = $amount;
      }
      return PaymentSpring::makeRequest("charge", $cardDetails, "POST");
    }
  }
