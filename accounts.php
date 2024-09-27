<?php
// root/accounts.php
session_start();
require 'src/config/config.php';
require 'src/Services/TokenManager.php';  
require 'src/Services/ApiClient.php';  
require 'src/Services/AccountsApi.php';  
require 'src/Services/ContactsApi.php';  

$tokenManager = new TokenManager();
$accessToken = $tokenManager->getAccessToken();  

$accountsApi = new AccountsApi($accessToken, $tokenManager);
$contactsApi = new ContactsApi($accessToken);

// Define pagination variables dynamically from query parameters
$pageNumber = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$pageSize = 10; // Number of accounts to fetch per page

// Get all accounts with pagination
$accounts = $accountsApi->getAllAccounts('name', $pageNumber, $pageSize);

// Error Handling: If API response is invalid
if (!isset($accounts['data'])) {
    echo "<p>Error fetching accounts. Please try again later.</p>";
    exit;
}

if (!empty($accounts['data'])) {
    // Display the accounts and related contacts
    foreach ($accounts['data'] as $account) {
        // Display account information
        $accountName = htmlspecialchars($account['attributes']['name']);
        $billingCity = htmlspecialchars($account['attributes']['billing_address_city']);
        echo "<h2>Account: $accountName - $billingCity</h2>";

        // Retrieve contacts related to this account
        $contacts = $contactsApi->getContactsByUrl($account['id']);

        if (isset($contacts['data']) && !empty($contacts['data'])) {
            echo "<ul>";
            foreach ($contacts['data'] as $contact) {
                $firstName = !empty($contact['attributes']['first_name']) ? htmlspecialchars($contact['attributes']['first_name']) : 'N/A';
                $lastName = !empty($contact['attributes']['last_name']) ? htmlspecialchars($contact['attributes']['last_name']) : 'N/A';
                $email = !empty($contact['attributes']['email1']) ? htmlspecialchars($contact['attributes']['email1']) : 'N/A';
                $phone = !empty($contact['attributes']['phone_home']) ? htmlspecialchars($contact['attributes']['phone_home']) : 'N/A';
                $title = !empty($contact['attributes']['title']) ? htmlspecialchars($contact['attributes']['title']) : 'N/A';
                $department = !empty($contact['attributes']['department']) ? htmlspecialchars($contact['attributes']['department']) : 'N/A';

                echo "<li>";
                echo "<strong>Name:</strong> $firstName $lastName<br>";
                echo "<strong>Contact Info:</strong> $email<br>";
                echo "<strong>Tel:</strong> $phone<br>";
                echo "<strong>Title:</strong> $title<br>";
                echo "<strong>Department:</strong> $department<br><br>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No contacts found for this account or invalid response from the API.</p>";
        }
    }

    // Display pagination links
    echo '<div style="margin-top: 20px;">';
    if ($pageNumber > 1) {
        echo '<a href="?page=' . ($pageNumber - 1) . '">Previous</a> | ';
    }
    if (count($accounts['data']) === $pageSize) {
        echo '<a href="?page=' . ($pageNumber + 1) . '">Next</a>';
    }
    echo '</div>';
} else {
    echo "<p>No accounts found or invalid response from the API.</p>";
}
