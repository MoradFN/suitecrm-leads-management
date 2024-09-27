<?php
//root/index.php
session_start();

require 'src/config/config.php';

require 'src/Services/ApiClient.php';  // Loads the ApiClient class to make API requests.
require 'src/Services/TokenManager.php';  // Loads the TokenManager class for handling access tokens.

require 'src/Services/AccountsApi.php'; 
require 'src/Services/ContactsApi.php'; 
require 'src/Services/NotesApi.php';  

$tokenManager = new TokenManager();
$accessToken = $tokenManager->getAccessToken();  // Get token from session or refresh

// Pass both the accessToken and the tokenManager to the AccountsApi constructor
$accountsApi = new AccountsApi($accessToken, $tokenManager);  // Instantiate the API class with the token and token manager
$contactsApi = new ContactsApi($accessToken);  // Assuming you will handle token management separately for ContactsApi
$notesApi = new NotesApi($accessToken);

// Get all accounts
$accounts = $accountsApi->getAllAccounts();

// Create an account
$newAccount = $accountsApi->createAccount("New Account");

// Search for an account
$searchResults = $accountsApi->searchAccount("sup%", "example@example.com");

// Update an account
$updatedAccount = $accountsApi->updateAccount("account_id", "Updated Name", "updated@example.com");

// Get all contacts
$contacts = $contactsApi->getAllContacts();

// Delete a specific note
$deleteNote = $notesApi->deleteNoteById("note_id");

// Output results
echo json_encode($accounts, JSON_PRETTY_PRINT);
echo json_encode($contacts, JSON_PRETTY_PRINT);
