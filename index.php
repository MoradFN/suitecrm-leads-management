<?php
//root/index.php
session_start();
require 'src/config/config.php';
require 'src/Services/ApiClient.php';  // Loads the ApiClient class to make API requests.
require 'src/Services/TokenManager.php';  // Loads the TokenManager class for handling access tokens.

// Token management
$tokenManager = new TokenManager();  // Creates an instance of TokenManager.
$accessToken = $tokenManager->getAccessToken();  // Attempts to retrieve the access token from the session. If not found, it will redirect to refresh_token.php.

$apiUrl = 'http://192.168.1.82/legacy/Api/V8/module/Accounts';  // The URL for the API endpoint to retrieve accounts.

try {
    $apiClient = new ApiClient($accessToken);  // Creates an instance of ApiClient and passes the access token.
    $responseData = $apiClient->callApi($apiUrl);  // Calls the API using the access token.

    if (isset($responseData['error'])) {
        if ($responseData['error'] === 'access_denied') {
            // If access is denied (e.g., the token is invalid), redirect to refresh_token.php to get a new token.
            header("Location: refresh_token.php");
            exit();
        } else {
            echo "API Error: " . $responseData['error_description'];  // Handles other API errors.
        }
    } else {
        // Successfully retrieved accounts, display them.
        echo json_encode($responseData, JSON_PRETTY_PRINT);  // Outputs the response in a readable format.
    }
} catch (Exception $e) {
    // If there is an exception (such as a cURL error), display the error.
    echo 'Error: ' . $e->getMessage();
}
