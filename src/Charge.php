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

    public static function chargeCustomer($customerID, $amount, $options = array()){
      $options["customer_id"] = $customerID;
      $options["amount"] = $amount;
      return self::chargeCard($options);
    }

    public static function chargeToken($token, $amount, $options = array()){
      $options["token"] = $token;
      return self::chargeCard($options, $amount);
    }
  }
