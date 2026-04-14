<?php
// Setup the MPESA API key and secret
$consumerKey = ""; //Get the Consumer Key for Safaricom API from the developer account
$consumerSecret = ""; // Get the Consumer Secret for Safaricom API from the developer account

// Setup the access token URL
$access_token_url = "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";

$headers = ["Content-Type:application/json; charset=utf8"];

$curl = curl_init($access_token_url);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_HEADER, FALSE);
curl_setopt($curl, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);

// Execute the curl request and get the access token
$result = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// Decode the JSON response to get the access token
$result = json_decode($result);

// Store the access token in a variable for later use
$access_token = $result->access_token;

// Close the curl session
// curl_close($curl);
// This function is deprecated in PHP 8.0 and later versions, so we will use unset() instead to free up the resources used by the curl session
unset($curl);
$curl = null;
?>