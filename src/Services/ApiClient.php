<?php
// src/Services/ApiClient.php
// src/Services/ApiClient.php
class ApiClient
{
    protected $accessToken;

    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    protected function request($method, $url, $data = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type: application/vnd.api+json',
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);
        return json_decode($response, true);
    }

    public function get($url)
    {
        return $this->request('GET', $url);
    }

    public function post($url, $data)
    {
        return $this->request('POST', $url, $data);
    }

    public function patch($url, $data)
    {
        return $this->request('PATCH', $url, $data);
    }

    public function delete($url)
    {
        return $this->request('DELETE', $url);
    }
}

