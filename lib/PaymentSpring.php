<?php

namespace PaymentSpring;

class PaymentSpring {

  public static $apiBaseURL = "https://api.paymentspring.com/api/v1/charge";

  protected static $publicKey, $privateKey;

  public static function setApiKeys($publicKey, $privateKey){
    self::$publicKey = $publicKey;
    self::$privateKey = $privateKey;
  }

  public function __constructor($public_key, $private_key){
    $this->private_key = $private_key;
  }

  public function construct_request ( $params, $type ) {
    $args = array(
      "method" => $type,
      "headers" => array(
        "Authorization" => "Basic " . base64_encode( $this->private_key . ":" )
      ),
      "body" => $params
    );
    //return wp_remote_post( GFPaymentSpring::apiURL, $args );
  }


  /**
   * Mark any PS CC fields as not required before performing validation. 
   * This is needed because the values of the CC fields are not sent to the 
   * server, causing validation to fail. CC validation is handled on the client
   * side.
   *
   * gform_pre_validation
   */
  public static function remove_cc_field_requirement ( $form ) {
    $cc_field = &GFPaymentSpring::get_credit_card_field ( $form );
    if ( GFPaymentSpring::is_paymentspring_field( $cc_field ) ) {
      $cc_field["isRequired"] = false;
    }
    return $form;
  }

  /**
   * Adds the "Transaction Mode" entry meta key to all PS form entries. The
   * value of the meta column is populated in process_transaction.
   *
   * gform_entry_meta
   */
  public static function account_mode_meta ( $entry_meta, $form_id ) {
    $forms = RGFormsModel::get_form_meta_by_id( $form_id );
    if ( GFPaymentSpring::is_paymentspring_form( $forms[0] ) ) {
      // The below key corresponds to meta_key in the wp_rg_lead_meta table
      $entry_meta["gf_paymentspring_transaction_mode"] = array(
        "label" => __( "Transaction Mode", "gf_paymentspring" ),
        "is_numeric" => false,
        "is_default_column" => false
      );
    }
    return $entry_meta;
  }

  /**
   * Displays the account mode the transaction was made under in the Info panel
   * on the entry view page.
   * 
   * gform_entry_info
   */
  public static function account_mode_entry_info ( $form_id, $lead ) {
    $forms = RGFormsModel::get_form_meta_by_id( $form_id );
    if ( GFPaymentSpring::is_paymentspring_form( $forms[0] ) ) {
      echo __( "Transaction Mode" ) . ": " . gform_get_meta( $lead["id"], "gf_paymentspring_transaction_mode" );
    }
  }

  public static function validate_form ( $validation_result ) {
    $form = &$validation_result["form"];
    if ( ! GFPaymentSpring::is_paymentspring_form( $form ) ) {
      return $validation_result;
    }

    $cc_field = &GFPaymentSpring::get_credit_card_field( $form );
    $current_page = rgpost( "gform_source_page_number_" . $form["id"] );

    if ( $validation_result["is_valid"] == false
         || $cc_field == false 
         || $current_page != $cc_field["pageNumber"]
         || RGFormsModel::is_field_hidden( $form, $cc_field, array( ) ) ) {
      // We don't need to validate this form/page.
      return $validation_result;
    }

    $token_id = rgpost( "token_id" );
    $ps_fields = GFPaymentSpring::paymentspring_fields( );
    foreach( GFPaymentSpring::paymentspring_fields( ) as $key => $value ) {
      $id = rgar( $cc_field, "field_paymentspring_{$key}" );
      if ( $id ) {
        $ps_fields[$key] = rgpost( "input_" . str_replace( ".", "_", $id ) );
      }
      else {
        $ps_fields[$key] = null;
      }
    }

    $amount_field = &GFPaymentSpring::get_field_by_id( $form, rgar( $cc_field, "field_paymentspring_amount" ) );
    $amount = $ps_fields["amount"];
    error_log( print_r( $amount, true ) );

    if ( ! $amount_field ) {
      $validation_result["is_valid"] = false;
      $cc_field["failed_validation"] = true;
      $cc_field["validation_message"] = __( "Amount field configured incorrectly.", "gf_paymentspring" );
      return $validation_result;
    }

    if ( strpos( $amount, "." ) !== false || strpos( $amount, "," ) !== false ) {
      // The amount field is in a "$1,234.56" format, strip out non-numeric
      // characters to yield the charge amount in cents, e.g. "123456".
      $amount = preg_replace( "/[^0-9]/", "", $amount );
    }
    else {
      // The Total field returns the amount as an integer dollar amount for
      // some reason, convert to cents by multiplying by 100 and truncating.
      $amount = intval( $amount * 100 );
    }

    $ps_fields["amount"] = $amount;

    if ( $token_id == false ) {
      $validation_result["is_valid"] = false;
      $cc_field["failed_validation"] = true;
      $cc_field["validation_message"] = __( "A PaymentSpring token could not be created.", "gf_paymentspring" );
      return $validation_result;
    }

    error_log( print_r( $amount, true ) );

    if ( ! $amount || $amount < 0 ) {
      $validation_result["is_valid"] = false;
      $amount_field["failed_validation"] = true;
      $amount_field["validation_message"] = __( "Invalid purchase amount.", "gf_paymentspring" );
      return $validation_result;
    }

    $ps_fields["token"] = $token_id;
    $response = GFPaymentSpring::post_charge( $ps_fields );
    error_log( print_r( $response, true ) );

    if ( is_wp_error( $response ) ) {
      $validation_result["is_valid"] = false;
      $cc_field["failed_validation"] = true;
      $cc_field["validation_message"] = __( "Could not connect to PaymentSpring. Please contact the site administrator.", "gf_paymentspring" ) 
        . "<br />" . $response->get_error_message();
      return $validation_result;
    }

    if ( ! in_array( $response["response"]["code"], array( 200, 201 ) ) ) {
      $validation_result["is_valid"] = false;
      $cc_field["failed_validation"] = true;
      $cc_field["validation_message"] = __( "Your card could not be charged.", "gf_paymentspring" ) . "<br />" . GFPaymentSpring::format_json_errors( $response["body"] );
      return $validation_result;
    }

    GFPaymentSpring::$transaction = $response["body"];

    return $validation_result;
  }

  public static function format_json_errors ( $json_errors ) {
    $errors = json_decode( $json_errors );
    $errors = $errors->errors;
    $str = "";
    foreach ( $errors as $error ) {
      $str .= "Code " . $error->code . " : " . $error->message . "<br />";
    }
    return $str;
  }

  /**
   * If an additional validation hook is executed after our validation hook and
   * fails GF will prompt the user to fix their error and resubmit, even though
   * their card has already been charged. If a transaction object exists when
   * the validation message is being generated that means the card has already
   * been charged. Therefore we need to intercept the validation message.
   */
  public static function card_charged_message ( $validation_message ) {
    if ( ! empty( GFPaymentSpring::$transaction ) ) {
      $msg = __( "Your card has been charged, but there was an unrelated error. Do not resubmit the form. Please contact the site administrator.", "gf_paymentspring" );
      $s = "<div class='validation_error'>" . $msg . "</div><script>alert('" . $msg . "');</script>";
      $s .= "<script>jQuery(\"form[id^='gform_']\").submit(function(){alert('" . $msg . "');return false;});</script>";
      return $s;
    }
    return $validation_message;
  }

  /**
   * Stores transaciton details in GF entry object.
   *
   * gform_entry_post_save
   */
  public static function process_transaction ( $entry, $form ) {
    if ( empty( GFPaymentSpring::$transaction ) ) {
      return;
    }

    $response = json_decode( GFPaymentSpring::$transaction );
    $entry["payment_status"] = $response->status;
    $entry["payment_date"] = $response->created_at;
    $entry["transaction_id"] = $response->id;
    $entry["payment_amount"] = $response->amount_settled / 100;
    $entry["payment_method"] = "paymentspring";
    $entry["is_fulfilled"] = $response->status == "SETTLED";
    $entry["transaction_type"] = 1; // one-time payment vs. subscription

    RGFormsModel::update_lead( $entry );

    // Inserts the last 4 digits of the card into the wp_rg_lead_detail table
    $cc_field = GFPaymentSpring::get_credit_card_field( $form );
    $entry[$cc_field["id"] . ".1"] = $response->card_number;
    GFFormsModel::update_lead_field_value( $form, $entry, $cc_field, 0, $cc_field["id"] . ".1", $response->card_number );

    $options = get_option( "gf_paymentspring_account" );
    gform_update_meta( $entry["id"], "gf_paymentspring_transaction_mode", $options["mode"] );

    GFPaymentSpring::$transaction = "";
    return $entry;
  }

  /**
   * Sends token and charge amount to paymentspring servers to charge token
   */

  public static function paymentspring_fields () {
    return array(
      "amount" => "Amount",
      //"first_name" => "First Name",
      //"last_name" => "Last Name",
      "address_1" => "Address 1",
      "address_2" => "Address 2",
      "city" => "City",
      "state" => "State",
      "zip" => "Zip",
      "phone" => "Phone",
      "fax" => "Fax",
      "website" => "Website",
      "company" => "Company"
    );
  }



  /**
   * Stops credit card information from being set to the server by removing 
   * 'name' attributes on the input tags.
   *
   * gform_field_content
   */
  public static function block_card_field ( $input, $field, $value, $lead_id, $form_id ) {
    if ( $field["type"] == "creditcard" and GFPaymentSpring::is_paymentspring_field( $field ) and $lead_id == 0 ) {
      // Strip out name="input_X.X" attributes from credit card field.
      return preg_replace("/name\s*=\s*[\"']input_{$field['id']}\.\d+.*?[\"']/", "", $input);
    }
    else {
      return $input;
    }
  }


  public static function is_paymentspring_form ( $form ) {
    $cc_field = GFPaymentSpring::get_credit_card_field( $form );
    return GFPaymentSpring::is_paymentspring_field( $cc_field );
  }

  public static function is_paymentspring_field ( $field ) {
    return rgar( $field, "field_paymentspring_card" ) == true;
  }

  public static function get_private_key () {
    $options = get_option( "gf_paymentspring_account" );
    if ( $options["mode"] == "live" ) {
      return $options["live_private_key"];
    }
    else {
      return $options["test_private_key"];
    }
  }

}
