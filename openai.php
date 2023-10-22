<?php
// OpenAI API credentials and functions
// Set the OpenAI API credentials
require_once 'config.php';
$OPENAI_API_KEY = OPENAI_API_KEY ;
$ENGINE_NAME = ENGINE_NAME ; 
// Implement the OpenAI API integration
function openaiApiCall($prompt) {
    // Implement the logic to call the OpenAI API and get the response
    // Use the $prompt parameter to customize the AI response
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.openai.com/v1/engines/{$ENGINE_NAME}/completions",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS =>"{\n  \"prompt\": \"$prompt\",\n  \"max_tokens\": 50,\n  \"temperature\": 0,\n  \"top_p\": 1,\n  \"n\": 1\n}",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $OPENAI_API_KEY"
      ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    // Return the AI response as a string
    return $response;
}