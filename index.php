<?php
//root/index.php
session_start();

require 'src/config/config.php';

require 'src/Services/ApiClient.php';  // Loads the ApiClient class to make API requests.
require 'src/Services/TokenManager.php';  // Loads the TokenManager class for handling access tokens.

// require 'src/Services/AccountsApi.php'; //MOVED to accounts.php
require 'src/Services/ContactsApi.php'; 
require 'src/Services/NotesApi.php';  

$tokenManager = new TokenManager();
$accessToken = $tokenManager->getAccessToken();  // Get token from session or refresh

// Pass both the accessToken and the tokenManager to the AccountsApi constructor

$contactsApi = new ContactsApi($accessToken, $tokenManager);  // Assuming you will handle token management separately for ContactsApi
$notesApi = new NotesApi($accessToken, $tokenManager);


// // MOVED to accounts.php
// $accountsApi = new AccountsApi($accessToken, $tokenManager);  // Instantiate the API class with the token and token manager
// // Get all accounts
// $accounts = $accountsApi->getAllAccounts();
// // // Create an account
// $newAccount = $accountsApi->createAccount("New Account");
// // Search for an account
// $searchResults = $accountsApi->searchAccount("sup%", "example@example.com");
// // // Update an account
// $updatedAccount = $accountsApi->updateAccount("account_id", "Updated Name", "updated@example.com");


// Get all contacts
// $contacts = $contactsApi->getAllContacts();

// Get all notes
// $notes = $notesApi->getAllNotes();




// // Delete a specific note
// $deleteNote = $notesApi->deleteNoteById("note_id");

// Output results
// echo json_encode($accounts, JSON_PRETTY_PRINT);
// echo json_encode($contacts, JSON_PRETTY_PRINT);
// echo json_encode($notes, JSON_PRETTY_PRINT);
// echo json_encode($searchResults, JSON_PRETTY_PRINT);
// echo json_encode($newAccount, JSON_PRETTY_PRINT);
// echo json_encode($updatedAccount, JSON_PRETTY_PRINT);
// echo json_encode($deleteNote, JSON_PRETTY_PRINT);

// You can now redirect users to different pages like accounts.php, contacts.php, etc., instead of doing everything in one place.
echo "<h1>Welcome to the Dashboard</h1>";
echo "<a href='accounts.php'>View Accounts</a><br>";
echo "<a href='contacts.php'>View Contacts</a><br>";
echo "<a href='notes.php'>View Notes</a><br>";