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

    public static function chargeCustomer($customerID, $amount, $details = array()){
      $details["customer_id"] = $customerID;
      $details["amount"] = $amount;
      return self::chargeCard($details);
    }

    public static function chargeToken($token, $amount){
      return self::chargeCard(array("token" => $token), $amount);
    }
  }
