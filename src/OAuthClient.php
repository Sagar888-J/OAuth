<?php

namespace Sagarj\OauthModule;

class OAuthClient
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $tokenUrl;
    private $authUrl;
    private $scopes;

    private $accessTokenFile = __DIR__ . './accessToken.txt';
    private $refreshTokenFile = __DIR__ . './refreshToken.txt';

    public function __construct()
    {
        $config = require __DIR__ . '/config.php';

        $this->clientId = $config['client_id'];
        $this->clientSecret = $config['client_secret'];
        $this->redirectUri = $config['redirect_uri'];
        $this->tokenUrl = $config['token_url'];
        $this->authUrl = $config['auth_url'];
        $this->scopes = $config['scopes'];
    }

    public function getAuthUrl()
    {
        return "{$this->authUrl}?client_id={$this->clientId}&redirect_uri={$this->redirectUri}&response_type=code&scope={$this->scopes}&access_type=offline&prompt=consent&state=123";
    }

    public function getAccessToken($authCode)
    {
        $postData = [
            "code" => $authCode,
            "client_id" => $this->clientId,
            "client_secret" => $this->clientSecret,
            "redirect_uri" => $this->redirectUri,
            "grant_type" => "authorization_code",
        ];

        $response = $this->makeRequest($this->tokenUrl, $postData);

        if (!isset($response['access_token']) || !isset($response['refresh_token'])) {
            return ["error" => "Failed to retrieve tokens", "response" => $response];
        }

        $this->saveToken($this->accessTokenFile, $response['access_token']);
        $this->saveToken($this->refreshTokenFile, $response['refresh_token']);

        return $response;
    }

    public function refreshAccessToken()
    {
        $refreshToken = $this->loadToken($this->refreshTokenFile);

        if (!$refreshToken) {
            return ["error" => "No valid refresh token found."];
        }

        $postData = [
            "refresh_token" => $refreshToken,
            "client_id" => $this->clientId,
            "client_secret" => $this->clientSecret,
            "grant_type" => "refresh_token",
        ];

        $response = $this->makeRequest($this->tokenUrl, $postData);

        if (!isset($response['access_token'])) {
            return ["error" => "Failed to refresh access token", "response" => $response];
        }

        $this->saveToken($this->accessTokenFile, $response['access_token']);

        if (isset($response['refresh_token'])) {
            $this->saveToken($this->refreshTokenFile, $response['refresh_token']);
        }

        return $response;
    }

    private function makeRequest($url, $postData)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    private function saveToken($file, $token)
    {
        if (filesize($file) == 0) {
            file_put_contents($file, $token);
        }
    }

    private function loadToken($file)
    {
        return file_exists($file) ? file_get_contents($file) : null;
    }
}
