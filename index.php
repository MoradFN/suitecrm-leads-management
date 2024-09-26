<?php
session_start();
require 'vendor/autoload.php'; 

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Check if access token is in session
if (!isset($_SESSION['access_token'])) {
    // If no access token, go to refresh token page
    header("Location: refresh_token.php");
    exit();
}
$accessToken = $_SESSION['access_token'];
$apiUrl = 'http://192.168.1.82/legacy/Api/V8/module/Accounts';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/vnd.api+json'
]);
// Execute the request
$response = curl_exec($ch);
curl_close($ch);

echo $response;


$responseData = json_decode($response, true);
if (isset($responseData['error'])) {
    // Handle token errors specifically
    if ($responseData['error'] === 'access_denied') {
        // Redirect to refresh token page
        header("Location: refresh_token.php");
        exit();
    } else {
        // Handle other API errors
        echo "API Error: " . $responseData['error_description'];
    }
} else {
    // Successfully retrieved accounts, display them
    echo $response;
}
