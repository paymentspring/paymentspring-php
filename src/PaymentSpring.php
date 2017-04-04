<?php

namespace PaymentSpring;

class PaymentSpring {


  /* Set the URL for PaymentSpring's API */
  public static $apiBaseURL = "https://api.paymentspring.com/api/v1/";

  public static $publicKey, $privateKey;

  /*
    * @param $publicKey string
    * @param $privateKey string
    * @return null; 
  */
  public static function setApiKeys($publicKey, $privateKey){
    self::$publicKey = $publicKey;
    self::$privateKey = $privateKey;
  }

  /*
    *
    * Base for all requests to PaymentSpring API
    * Most functions using makeRequest will convert the response into a JSON object.
    *
    * @param $path string
    * @param $params array optional 
    * @param $isPost boolean optional 
    * @return string
  */
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

  /*
    *
    * Adds headers for curl POST request 
    *
    * @param $options array 
    * @param $params array 
    * @return array 
  */
  
  public static function constructPostRequest($options, $params){
    $options[CURLOPT_POST] = TRUE;
    $options[CURLOPT_POSTFIELDS] = $params;
    return $options;
  }
  

}
