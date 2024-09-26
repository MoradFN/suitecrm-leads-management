<?php
// src/Services/TokenManager.php
class TokenManager
{
    public function getAccessToken()
    {
        // Check if the access token is in the session
        if (isset($_SESSION['access_token'])) {
            return $_SESSION['access_token'];  // Returns the token if it exists in the session.
        }

       // If not, redirect to refresh_token.php to get a new token
       header("Location: refresh_token.php");
       exit();
    }

    public function setAccessToken($token)
    {
        // Stores the access token in the session
        $_SESSION['access_token'] = $token;
    }
}
