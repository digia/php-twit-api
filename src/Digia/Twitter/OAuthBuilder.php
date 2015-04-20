<?php

namespace Digia\Twitter;

class OAuthBuilder 
{
    /**
     * Twitter API configuration settings
     *
     * @var Digia\Twitter\Config
     */
    protected $config;

    /**
     * API request environment
     *
     * @var Digia\Twitter\Environment
     */
    protected $env;


    public function createHeader(Config $config, Environment $env)
    {
        $this->config = $config;
        $this->env = $env;

        $oAuthString = $this->generateOAuthString();

        return [
            'Authorization: OAuth ' . $oAuthString,
            'Expect:'
            ];
    }

    /**
     * Get the OAuth parameters are a string
     *
     * @return string
     */
    protected function generateOAuthString()
    {
        $params = array_merge($this->getOAuthParams(), ['oauth_signature' => $this->generateOAuthSignature()]);
        ksort($params);

        $data = [];

        foreach ($params as $key => $val) {
            $data[] = $key . '="' . rawurlencode($val) . '"';
        }

        $oAuthString = implode(', ', $data);

        return $oAuthString;
    }

    /**
     * Get the OAuth Paramater used in the request header
     *
     * @return array
     */
    protected function getOAuthParams()
    {
        return [
            'oauth_consumer_key' => $this->config->consumerKey,
            'oauth_nonce' => trim(base64_encode( pow(time(), 3) ), '='),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_token' => $this->config->oAuthToken,
            'oauth_version' => '1.0'
        ];
    }

    /**
     * Generate OAuth Signature using HMAC-SHA1 & base64
     *
     * @return string
     */
    protected function generateOAuthSignature()
    {
        $hash = hash_hmac('sha1', $this->generateSignatureBaseString(), $this->generateSignatureBaseStringKey(), true);

        return base64_encode($hash);
    }

    /**
     * Generate a request signature string
     *
     * @return string
     */
    protected function generateSignatureBaseString()
    {
        $method = strtoupper($this->env->getMethod());

        $url = rawurlencode($this->env->getRequestUrl());

        return $method . '&' . $url . '&' . $this->generateParamString();
    }

    /**
     * Generate a url encoded request string containing the parameters.
     *
     * @return string
     */
    protected function generateParamString()
    {
        $params = array_merge($this->env->getGetParams(), $this->env->getPostParams(), $this->getOAuthParams());

        $params = $this->env->paramsToString($params);

        return rawurlencode($params);
    }

    /**
     * Generate OAuth signing key
     *
     * @return string
     */
    protected function generateSignatureBaseStringKey()
    {
        return $this->config->consumerSecret . '&' . $this->config->oAuthTokenSecret;
    }

}
