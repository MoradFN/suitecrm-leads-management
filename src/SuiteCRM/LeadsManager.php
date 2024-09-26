<?php

namespace SuiteCRM;

class LeadsManager {
    private $tokenManager;

    public function __construct(TokenManager $tokenManager) {
        $this->tokenManager = $tokenManager;
    }

    // Retrieve target lists
    public function getTargetLists() {
        return $this->tokenManager->makeApiRequest('/TargetLists');
    }

    // Log call outcome
    public function logCallOutcome($contactId, $outcome) {
        $data = [
            'data' => [
                'type' => 'Calls',
                'attributes' => [
                    'contact_id' => $contactId,
                    'status' => $outcome,
                ]
            ]
        ];

        $response = $this->tokenManager->makeApiRequest('/Calls', 'POST', $data);

        if ($response->getStatusCode() !== 201) {
            throw new \Exception('Failed to log call outcome');
        }

        return $response;
    }
}

