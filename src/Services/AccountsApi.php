<?php
// src/Services/AccountsApi.php

class AccountsApi extends ApiClient
{
    private $baseUrl = 'http://192.168.1.82/legacy/Api/V8/module/Accounts';
    private $tokenManager;

    public function __construct($accessToken, $tokenManager)
    {
        parent::__construct($accessToken);
        $this->tokenManager = $tokenManager;
    }

    // Refresh token handling logic
    private function handleTokenExpiration($callback)
    {
        // Call the API
        $response = $callback();

        // If the token is expired or access is denied, attempt to refresh the token
        if (isset($response['error']) && $response['error'] === 'access_denied') {
            // Refresh the token using TokenManager
            $this->tokenManager->refreshAccessToken();

            // Get the new access token
            $newAccessToken = $this->tokenManager->getAccessToken();

            // Set the new token in the parent ApiClient
            $this->setAccessToken($newAccessToken);

            // Retry the API call with the new token
            return $callback();
        }

        return $response;
    }

    // API method for getting all accounts with token refresh logic
    public function getAllAccounts($sort = 'name', $page = 1, $size = 10)
    {
        $callback = function () use ($sort, $page, $size) {
            $url = $this->baseUrl . "?sort=$sort&page[number]=$page&page[size]=$size";
            return $this->get($url);
        };

        // Handle token expiration with retry
        return $this->handleTokenExpiration($callback);
    }

    // API method for creating an account with token refresh logic
    public function createAccount($name)
    {
        $data = [
            "data" => [
                "type" => "Accounts",
                "attributes" => [
                    "name" => $name
                ]
            ]
        ];

        $callback = function () use ($data) {
            return $this->post($this->baseUrl, $data);
        };

        // Handle token expiration with retry
        return $this->handleTokenExpiration($callback);
    }

    // Other API methods like updateAccount(), getAccountById(), searchAccount() etc.
    public function updateAccount($accountId, $name, $email)
    {
        $url = $this->baseUrl . "/$accountId";
        $data = [
            "data" => [
                "id" => $accountId,
                "type" => "Accounts",
                "attributes" => [
                    "name" => $name,
                    "email1" => $email
                ]
            ]
        ];

        $callback = function () use ($url, $data) {
            return $this->patch($url, $data);
        };

        // Handle token expiration with retry
        return $this->handleTokenExpiration($callback);
    }

    public function getAccountById($accountId)
    {
        $callback = function () use ($accountId) {
            $url = $this->baseUrl . "/$accountId";
            return $this->get($url);
        };

        // Handle token expiration with retry
        return $this->handleTokenExpiration($callback);
    }

    public function searchAccount($name, $email)
    {
        $callback = function () use ($name, $email) {
            $url = $this->baseUrl . "?filter[operator]=or&filter[name][like]=$name&filter[email1][like]=$email";
            return $this->get($url);
        };

        // Handle token expiration with retry
        return $this->handleTokenExpiration($callback);
    }
}