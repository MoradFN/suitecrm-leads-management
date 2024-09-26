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

        // If not, redirect to refresh token
        header("Location: refresh_token.php");
        exit();
    }

    public function setAccessToken($token)
    {
        $_SESSION['access_token'] = $token;
    }
}
