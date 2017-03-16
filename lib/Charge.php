<?php
  namespace PaymentSpring;

  class Charge{
    public static function chargeCard($cardDetails, $amount = null){
      if($amount){
        $cardDetails["amount"] = $amount;
      }
      $response = PaymentSpring::makeRequest("charge", $cardDetails, true);
      return json_decode($response);
    }
  }
