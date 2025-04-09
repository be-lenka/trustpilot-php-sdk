<?php

namespace TrustPilot\Api;

use Carbon\Carbon;

class Authorize extends AbstractApi
{

    /**
     * @var String
     */
    protected $token;

    /**
     * @var String
     */
    protected $type_settings;

    /**
     * Set the token
     *
     * @param  string
     * @return string
     */
    public function setToken($token = '')
    {
        $this->token = $token;
    }

    /**
     * Get the toker
     *
     * @param  
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Get Authorization Token with password method
     *
     * @param  
     * @return 
     */
    public function createPasswordToken($username = '', $password = '')
    {
        $data = ['username' => $username, 'password' => $password];
        $this->token = $this->createToken('password', $data);
        return $this->token;
    }

    /**
     * Redirect to get Authorization Code
     *
     * @param  
     * @return 
     */
    public function redirectToAuth($apiKey, $redirect_uri = '')
    {
        header('Location: https://authenticate.trustpilot.com?client_id=' . $apiKey . '&redirect_uri=' . urlencode($redirect_uri) . '&response_type=code');
        exit;
    }

    /**
     * Get Authorization Token with Authrorization Code
     *
     * @param  
     * @return 
     */
    public function createAuthToken($code = '', $redirect_uri = '')
    {
        $data = ['code' => $code, 'redirect_uri' => $redirect_uri];
        $this->token = $this->createToken('authorization_code', $data);
        return $this->token;
    }

    /**
     * Get Authorization Token with authorization code
     *
     * @param  
     * @return 
     */
    protected function createToken($type, $data)
    {
        $body = [
            'grant_type' => $type
        ];
        $fullBody = array_merge($body, $data);

        return json_decode($this->api->post(
            'oauth/oauth-business-users-for-applications/accesstoken',
            [
                'form_params' => $fullBody
            ]
        ));
    }

    /**
     * Refresh the token
     *
     * @param 
     * @return 
     */
    public function refreshToken()
    {
        $response = json_decode($this->api->post(
            'oauth/oauth-business-users-for-applications/refresh',
            [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $this->token->refresh_token
                ]
            ]
        ));

        $this->token = $response;
        return $response;
    }

    /**
     * Validate Token
     *
     * @param  
     * @return 
     */
    public function isRefreshedToken()
    {
        if (!$this->isValidToken()) {
            $this->refreshToken();
            return true;
        }

        return false;
    }

    /**
     * Check if the token is valid
     *
     * @param  
     * @return 
     */
    public function isValidToken()
    {
        if (!isset($this->token)) {
            return false;
        }
        
        $issued_at = intval(substr($this->token->issued_at, 0, -3));
        $expires_in = intval($this->token->expires_in);
        $expiry = $issued_at + $expires_in;

        $now = Carbon::now()->timestamp;

        if ($now > ($expiry - 7200)) {
            return false;
        }

        return true;
    }
}
