<?php
// src/Services/ContactsApi.php
class ContactsApi extends ApiClient
{
    private $baseUrl = 'http://192.168.1.82/legacy/Api/V8/module/Contacts';

    public function getAllContacts()
    {
        return $this->get($this->baseUrl);
    }

    // Add more methods as needed for contacts
}