<?php
// First include the access token file to get the access token for the MPESA API
include "access_token.php";

// Set the default timezone to Nairobi time
date_default_timezone_set("Africa/Nairobi");

// Set the process request URL and the callback URL for the MPESA API
$query_url = "https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query";

// Set the business short code for the MPESA API
$business_short_code = "174379"; // confirm the business short code for the MPESA API from the developer account

// Create a timestamp variable in the format of YYYYMMDDHHMMSS 
$timestamp = date("YmdHis");

// Set the pass_key and the business short code for the MPESA API
$pass_key = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919"; // Get the pass key for the MPESA API from the developer account

// Perform base64 encoding to the passkey and the business short code and the timestamp to get the password for the MPESA API
$password = base64_encode($business_short_code . $pass_key . $timestamp);

// Unique checkout request ID for the MPESA API, make sure to use the checkout request ID that was generated when the STK push request was initiated successfully
$checkout_request_id = "{CHECKOUT_REQUEST_ID}"; // Set the checkout request ID for the MPESA API, make sure to use the checkout request ID that was generated when the STK push request was initiated successfully

// Create the headers for the MPESA API with the access token
$queryheader = ["Content-Type:application/json", "Authorization:Bearer" . $access_token];

// Initialize the curl session and set the curl options for the MPESA API
$curl = curl_init();

// Set the curl options for the MPESA API
curl_setopt($curl, CURLOPT_URL, $query_url);
curl_setopt($curl, CURLOPT_HTTPHEADER, $queryheader); //setting custom header

// Create the array of the request parameters for the MPESA API
$curl_post_data = array(
  "BusinessShortCode" => $business_short_code,
  "Password" => $password,
  "Timestamp" => $timestamp,
  "CheckoutRequestID" => $checkout_request_id
);

// Encode the request parameters to JSON format for the MPESA API
$data_string = json_encode($curl_post_data);

// Set the curl options for the MPESA API
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

// Execute the curl request and get the response from the MPESA API
$curl_response = curl_exec($curl);
// echo $curl_response;

// Decode the JSON response from the MPESA API to get the checkout request ID and the response code
$data_to = json_decode($curl_response);


// Check if the response from the MPESA API contains the result code and print the appropriate message based on the result code, otherwise print the error message
$message = "";
if (isset($data_to->ResultCode)) {
  $result_code = $data_to->ResultCode;
  switch ($result_code) {
    case '1037':
      $message = "1037 Timeout in completing transaction";
      break;
    case '1032':
      $message = "1032 Transaction  has cancelled by user";
      break;
    case '1':
      $message = "1 The balance is insufficient for the transaction";
      break;
    case '0':
      $message = "0 The transaction is successfully";
      break;
    default:
      $message = "Unknown result code: " . $result_code;
  }
}

echo $message;