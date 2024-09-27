<?php
// src/Services/NotesApi.php
class NotesApi extends ApiClient
{
    private $baseUrl = 'http://192.168.1.82/legacy/Api/V8/module/Notes';

    public function getAllNotes()
    {
        return $this->get($this->baseUrl);
    }

    public function deleteNoteById($noteId)
    {
        $url = $this->baseUrl . "/$noteId";
        return $this->delete($url);
    }

    
}
