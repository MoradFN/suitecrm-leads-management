<?php
// src/Services/ContactsApi.php

class ContactsApi extends ApiClient
{
    private $baseUrl = 'http://192.168.1.82/legacy/Api/V8/module/Contacts';

    // Constructor to pass the access token to the parent ApiClient class
    public function __construct($accessToken, $tokenManager)
    {
        parent::__construct($accessToken, $tokenManager);
    }

    // Get all contacts
    public function getAllContacts()
    {
        return $this->get($this->baseUrl);
    }
}