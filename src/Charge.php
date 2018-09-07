<?php
  namespace PaymentSpring;

  class Charge{

    /*
      *
      * Basic charge request 
      *
      * @param $cardDetails array 
      * @param $amount integer optional 
      * @return JSON Object 
    */

    public static function chargeCard($cardDetails, $amount = null){
      if($amount){
        $cardDetails["amount"] = $amount;
      }
      $response = PaymentSpring::makeRequest("charge", $cardDetails, true);
      return json_decode($response);
    }

    /*
      *
      * Charge a customer 
      *
      * @param $customerID integer 
      * @param $amount integer 
      * @param $options array optional 
      * @return JSON Object 
    */

    public static function chargeCustomer($customerID, $amount, $options = array()){
      $options["customer_id"] = $customerID;
      $options["amount"] = $amount;
      return self::chargeCard($options);
    }

    /*
      *
      * Charge a token, usually created in javascript before a payment request
      *
      * @param $token string 
      * @param $amount integer 
      * @param $options array optional 
      * @return JSON Object 
    */

    public static function chargeToken($token, $amount, $options = array()){
      $options["token"] = $token;
      return self::chargeCard($options, $amount);
    }

    /*
      *
      * Retrieve a Charge
      *
      * @param $chargeID string 
      * @return JSON Object 
    */

    public static function retrieveCharge($chargeID){
      $response = PaymentSpring::makeRequest("charge/".$chargeID);
      return json_decode($response);
    }
  }
