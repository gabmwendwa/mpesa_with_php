<?php
// First include the access token file to get the access token for the MPESA API
include "access_token.php";

// Set the default timezone to Nairobi time
date_default_timezone_set("Africa/Nairobi");

// Set the process request URL and the callback URL for the MPESA API
$process_request_url = "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest";
$callback_url = "{CALLBACK_URL}"; // Create a callback URL in your server and set it here to receive the response from the MPESA API after the transaction is completed

// Set the pass_key and the business short code for the MPESA API
$pass_key = "{PASS_KEY}"; // Get the pass key for the MPESA API from the developer account

// Set the business short code for the MPESA API
$business_short_code = "{BUSINESS_SHORT_CODE}"; // confirm the business short code for the MPESA API from the developer account

// Create a timestamp variable in the format of YYYYMMDDHHMMSS 
$timestamp = date("YmdHis");


// Perform base64 encoding to the passkey and the business short code and the timestamp to get the password for the MPESA API
$password = base64_encode($business_short_code . $pass_key . $timestamp);

// Set the phone number to receive the STK push and the amount to be paid
$phone = "{PHONE_NUMBER}"; // Set the phone number to receive the STK push, make sure to use the phone number in the format of 2547XXXXXXXX
$amount = "{AMOUNT}"; // Set the amount to be paid, make sure to use a valid amount for the MPESA API

// Set the account reference and the transaction description for the MPESA API
$account_reference = "{ACCOUNT_REFERENCE}"; // Set the account reference for the MPESA API
$transaction_desc = "{TRANSACTION_DESCRIPTION}"; // Set the transaction description for the MPESA API

// Create the headers for the MPESA API with the access token
$stk_push_header = ["Content-Type:application/json", "Authorization:Bearer " . $access_token];

// Initialize the curl session and set the curl options for the MPESA API
$curl = curl_init();

// Set the curl options for the MPESA API
curl_setopt($curl, CURLOPT_URL, $process_request_url);
curl_setopt($curl, CURLOPT_HTTPHEADER, $stk_push_header); //setting custom header

// Create the array of the request parameters for the MPESA API
$curl_post_data = array(
  //Fill in the request parameters with valid values
  "BusinessShortCode" => $business_short_code,
  "Password" => $password,
  "Timestamp" => $timestamp,
  "TransactionType" => "CustomerPayBillOnline",
  "Amount" => $amount,
  "PartyA" => $phone,
  "PartyB" => $business_short_code,
  "PhoneNumber" => $phone,
  "CallBackURL" => $callback_url,
  "AccountReference" => $account_reference,
  "TransactionDesc" => $transaction_desc
);


// Encode the request parameters to JSON format for the MPESA API
$data_string = json_encode($curl_post_data);

// Set the curl options for the MPESA API to send a POST request with the JSON data
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

// Execute the curl request and get the response from the MPESA API
$curl_response = curl_exec($curl);
echo $curl_response;


// Decode the JSON response from the MPESA API to get the checkout request ID and the response code
$data = json_decode($curl_response);

$checkout_request_id = $data->CheckoutRequestID;
// echo "Checkout request ID is : " . $checkout_request_id . "\n";

$response_code = $data->ResponseCode;
// echo "Response code is : " . $response_code . "\n";

// If the response code is 0, then the transaction was successful and we can print the checkout request ID, otherwise we can print the error message
echo $response_code == "0" ? "Transaction checkout request ID is : " . $checkout_request_id : "Transaction failed: " . $data->errorMessage;

?>