<?php
// src/Services/NotesApi.php

class NotesApi extends ApiClient
{
    private $baseUrl = 'http://192.168.1.82/legacy/Api/V8/module/Notes';

    // Constructor to pass the access token to the parent ApiClient class
    public function __construct($accessToken, $tokenManager)
    {
        parent::__construct($accessToken, $tokenManager);
    }

    // Get all notes
    public function getAllNotes()
    {
        return $this->get($this->baseUrl);
    }

    // Delete a note by its ID
    public function deleteNoteById($noteId)
    {
        $url = $this->baseUrl . "/$noteId";
        return $this->delete($url);
    }
}
