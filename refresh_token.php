<?php
session_start();
// root/refresh_token.php
require 'src/config/config.php';
// require 'vendor/autoload.php'; 

$apiUrl = 'http://192.168.1.82/legacy/Api/access_token';  // The API endpoint for obtaining a new access token.
$grant_type = 'password';
$client_id = '4bd530e1-4d74-c98b-d916-66ef7faab781'; //MTTODO: GET FROM ENV
$client_secret = 'secret';                           //MTTODO: GET FROM ENV
$username = $_ENV['CRM_USERNAME'];
$password = $_ENV['CRM_PASSWORD'];
$data = [
    'grant_type' => $grant_type,
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'username' => $username,
    'password' => $password
];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded'
]);


$response = curl_exec($ch);
curl_close($ch);



$responseData = json_decode($response, true);  // Parses the response.

if (isset($responseData['access_token'])) {
    // If the access token is successfully obtained, store it in the session
    $_SESSION['access_token'] = $responseData['access_token'];

    // Redirect back to index.php after successfully refreshing the token
    header("Location: index.php");
    exit();
} else {
    // If there was an error, display it
    echo 'Error refreshing token: ' . $response;
}