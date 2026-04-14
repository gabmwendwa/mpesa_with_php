<?php
// Set the content type to application/json for the response from the MPESA API
header("Content-Type: application/json");

// Get the response from the MPESA API after the transaction is completed and store it in a variable
$stk_callback_response = file_get_contents("php://input");

// Set the log file path to store the response from the MPESA API after the transaction is completed
$log_file = "/stk_response/stkPushCallbackResponse.json";

// Open the log file in append mode and write the response from the MPESA API to the log file for record keeping and debugging purposes 
$log = fopen($log_file, "a");
fwrite($log, $stk_callback_response);

// Close the log file after writing the response from the MPESA API to free up the resources used by the file
fclose($log);

// Decode the JSON response from the MPESA API to get the transaction details and store them in variables for later use
$data = json_decode($stk_callback_response);

$merchant_request_id = $data->Body->stkCallback->MerchantRequestID;
$checkout_request_id = $data->Body->stkCallback->CheckoutRequestID;
$result_code = $data->Body->stkCallback->ResultCode;
$result_desc = $data->Body->stkCallback->ResultDesc;
$amount = $data->Body->stkCallback->CallbackMetadata->Item[0]->Value;
$transaction_id = $data->Body->stkCallback->CallbackMetadata->Item[1]->Value;
$user_phone_number = $data->Body->stkCallback->CallbackMetadata->Item[4]->Value;

// Create an array to store the transaction details from the MPESA API for later use in the application, such as storing in a database or sending a notification to the user
$result_data = {
    "merchant_request_id" : $merchant_request_id,
    "checkout_request_id" : $checkout_request_id,
    "result_code" : $result_code,
    "result_desc" : $result_desc,
    "amount": $amount,
    "transaction_id" : $transaction_id,
    "user_phone_number" : $user_phone_number
};
?>