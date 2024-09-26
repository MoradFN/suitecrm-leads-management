<?php
session_start();
require 'src/config/config.php';
require 'src/Services/ApiClient.php';
require 'src/Services/TokenManager.php';

// Token management
$tokenManager = new TokenManager();
$accessToken = $tokenManager->getAccessToken();

$apiUrl = 'http://192.168.1.82/legacy/Api/V8/module/Accounts';

try {
    $apiClient = new ApiClient($accessToken);
    $responseData = $apiClient->callApi($apiUrl);

    if (isset($responseData['error'])) {
        if ($responseData['error'] === 'access_denied') {
            header("Location: refresh_token.php");
            exit();
        } else {
            echo "API Error: " . $responseData['error_description'];
        }
    } else {
        // Successfully retrieved accounts, display them
        echo json_encode($responseData, JSON_PRETTY_PRINT);
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
