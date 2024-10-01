<?php
// src/Services/TokenManager.php
class TokenManager
{
    public function getAccessToken()
    {
        // Check if the access token is in the session
        if (isset($_SESSION['access_token'])) {
            return $_SESSION['access_token'];
        }

        // If not, refresh the token
        $this->refreshAccessToken();
    }

    public function setAccessToken($token)
    {
        $_SESSION['access_token'] = $token;
    }

    // Add the refreshAccessToken() method
    public function refreshAccessToken()
    {
        // Logic to refresh the access token
        header("Location: refresh_token.php");  // Redirect to refresh_token.php to handle token refresh
        exit();
    }
}
