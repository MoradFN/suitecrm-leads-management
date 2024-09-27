<!-- 1. Table Structure (Backend Logic)
You will need to create three tables (or data structures) in your backend:
accounts: Stores company information.
contacts: Stores people associated with companies.
target_lists: Stores target lists, including which accounts belong to them and which employee is assigned to each target list.
call_logs: Tracks call attempts, results, and status for each contact.
2. API Class for Managing Target Lists (TargetListsApi.php)
Here’s a class that handles the logic for creating and assigning target lists: -->

<?php
// src/Services/TargetListsApi.php

class TargetListsApi extends ApiClient
{
    private $baseUrl = 'http://192.168.1.82/legacy/Api/V8/module/TargetLists';
    private $tokenManager;

    public function __construct($accessToken, $tokenManager)
    {
        parent::__construct($accessToken);
        $this->tokenManager = $tokenManager;
    }

    // Create a new target list
    public function createTargetList($name)
    {
        $data = [
            "data" => [
                "type" => "TargetLists",
                "attributes" => [
                    "name" => $name,
                ]
            ]
        ];

        return $this->post($this->baseUrl, $data);
    }

    // Assign a target list to an employee
    public function assignTargetList($targetListId, $employeeId)
    {
        $data = [
            "data" => [
                "id" => $targetListId,
                "type" => "TargetLists",
                "attributes" => [
                    "assigned_user_id" => $employeeId,
                ]
            ]
        ];

        return $this->patch($this->baseUrl . "/$targetListId", $data);
    }

    // Add an account to a target list
    public function addAccountToTargetList($targetListId, $accountId)
    {
        $url = $this->baseUrl . "/$targetListId/link/Accounts";
        $data = [
            "data" => [
                "id" => $accountId,
                "type" => "Accounts"
            ]
        ];

        return $this->post($url, $data);
    }

    // Retrieve all target lists
    public function getAllTargetLists()
    {
        return $this->get($this->baseUrl);
    }
}
