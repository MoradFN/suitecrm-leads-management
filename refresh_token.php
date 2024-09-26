<?php
session_start();
require 'vendor/autoload.php'; 

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiUrl = 'http://192.168.1.82/legacy/Api/access_token';
$grant_type = 'password';
$client_id = '4bd530e1-4d74-c98b-d916-66ef7faab781';
$client_secret = 'secret';
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

echo $response;




$responseData = json_decode($response, true);
if (isset($responseData['access_token'])) {
    $_SESSION['access_token'] = $responseData['access_token'];
    // Redirect back to index.php after successful refresh
    header("Location: index.php");
    exit();
} else {
    // Handle token refresh errors
    echo 'Error refreshing token: ' . $response;
}