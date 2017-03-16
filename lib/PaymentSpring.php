<?php

namespace PaymentSpring;

class PaymentSpring {

  public static $apiBaseURL = "https://api.paymentspring.com/api/v1/";

  public static $publicKey, $privateKey;

  public static function setApiKeys($publicKey, $privateKey){
    self::$publicKey = $publicKey;
    self::$privateKey = $privateKey;
  }

  public static function makeRequest($path, $params = array(), $isPost = false){
    $process = curl_init();
    $curlOptions = array(
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => self::$apiBaseURL . $path,
      CURLOPT_HTTPHEADER => array('Authorization: Basic '. base64_encode( self::$privateKey . ":" ) )
    );
    if($isPost){
      $curlOptions = self::constructPostRequest($curlOptions, $params);
    }    
    curl_setopt_array($process, $curlOptions);
    $result = curl_exec($process);
    curl_close($process); 
    return $result;
  }
  
  public static function constructPostRequest($options, $params){
    $options[CURLOPT_POST] = TRUE;
    $options[CURLOPT_POSTFIELDS] = $params;
    return $options;
  }
  

}
