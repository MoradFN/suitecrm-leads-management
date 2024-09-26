<?php

namespace SuiteCRM;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TokenManager {
    private $client;
    private $clientId;
    private $clientSecret;
    private $apiTokenUrl;
    private $apiBaseUrl;

    public function __construct() {
        // Load environment variables
        $this->client = new Client();
        $this->clientId = getenv('CRM_CLIENT_ID');
        $this->clientSecret = getenv('CRM_CLIENT_SECRET');
        $this->apiTokenUrl = getenv('CRM_API_TOKEN_URL');  // Access token URL
        $this->apiBaseUrl = getenv('CRM_API_BASE_URL');    // Base API URL

        // Check if variables are correctly set
        if (!$this->clientId || !$this->clientSecret || !$this->apiTokenUrl || !$this->apiBaseUrl) {
            throw new \Exception('Missing environment variables!');
        }
    }

    // Get access token
    public function getAccessToken() {
        try {
            // Log the API token URL and other vars to ensure they're being set
            var_dump($this->apiTokenUrl, $this->clientId, $this->clientSecret);
    
            $response = $this->client->post($this->apiTokenUrl, [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ]
            ]);
    
            // Parse the response
            $data = json_decode($response->getBody()->getContents(), true);
    
            if (isset($data['access_token'])) {
                return $data['access_token'];
            } else {
                throw new \Exception('Failed to retrieve access token');
            }
        } catch (RequestException $e) {
            // Log Guzzle exceptions
            var_dump('Error in Guzzle request: ', $e->getMessage());
        } catch (\Exception $e) {
            // Catch all other exceptions
            var_dump('General error: ', $e->getMessage());
        }
    }
}
