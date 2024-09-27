<?php
// src/Services/ContactsApi.php

class ContactsApi extends ApiClient
{
    private $baseUrl = 'http://192.168.1.82/legacy/Api/V8/module/Contacts';

    // Constructor to pass the access token to the parent ApiClient class

    // public function __construct($accessToken, $tokenManager)
    // {
    //     parent::__construct($accessToken, $tokenManager);
    // }
    public function __construct($accessToken)
    {
        parent::__construct($accessToken);  // Only pass the access token to ApiClient
    }

    // Get all contacts
    public function getAllContacts()
    {
        return $this->get($this->baseUrl);
    }

    // Fetch related contacts for a specific account
    public function getContactsByUrl($accountId)
    {
        $url = 'http://192.168.1.82/legacy/Api/V8/module/Accounts/' . $accountId . '/relationships/contacts'; 
        return $this->get($url);
    }
}
