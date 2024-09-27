<?php
// root/accounts.php
session_start();
require 'src/config/config.php';
require 'src/Services/TokenManager.php';  // Loads the TokenManager class for handling access tokens.
require 'src/Services/ApiClient.php';  // Loads the ApiClient class to make API requests
require 'src/Services/AccountsApi.php';  // Loads the AccountsApi class
require 'src/Services/ContactsApi.php';  // Loads the ContactsApi class

$tokenManager = new TokenManager();
$accessToken = $tokenManager->getAccessToken();  // Get token from session or refresh

$accountsApi = new AccountsApi($accessToken, $tokenManager);
$contactsApi = new ContactsApi($accessToken);

// Get all accounts
$accounts = $accountsApi->getAllAccounts();

// Display the accounts and related contacts
foreach ($accounts['data'] as $account) {
    echo "<h2>Account: " . htmlspecialchars($account['attributes']['name']) . "</h2>";

    // Retrieve contacts related to this account
    $contacts = $contactsApi->getContactsByUrl($account['id']);
    
if (isset($contacts['data']) && !empty($contacts['data'])) {
        echo "<ul>";
        foreach ($contacts['data'] as $contact) {
            echo "<li>" . htmlspecialchars($contact['attributes']['first_name']) . " " . htmlspecialchars($contact['attributes']['last_name']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No contacts found for this account or invalid response from the API.</p>";
    }
}
?>

