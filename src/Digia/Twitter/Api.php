<?php

namespace Digia\Twitter;

class Api {

    /**
     * Twitter configuration settings
     *
     * @var Digia\Twitter\Config
     */
    protected $config;


    /**
     * Environment settings
     *
     * @var Digia\Twitter\Environment
     */
    protected $env;


    public function __construct(Config $config, Environment $env)
    {
        $this->config = $config;
        $this->env = $env;
    }

    /**
     * Set the HTTP method to get and add the url 
     *
     * @param string $api
     * 
     * @return $this
     */
    public function get($api)
    {
        $this->env->setMethod('GET');

        $this->buildUrl($api);

        return $this;
    }

    /**
     * Build the complete HTTP url 
     *
     * @param string $api
     */
    private function buildUrl($api)
    {
        $base = $this->env->getApiUrl();

        $this->env->setRequestUrl($base . trim($api, '/') . '.json');
    }

    /**
     * Add a param to the request 
     * 
     * @param string $name
     * @param string $val
     * @param string $method
     *
     * @return $this
     */
    public function param($name, $val, $method='GET')
    {
        $method = strtoupper($method);

        if ($method === 'GET') $this->env->setGetParam($name, $val);

        if ($method === 'POST') $this->env->setPostParam($name, $val);

        return $this;
    }

    /**
     * Add params to the request 
     * 
     * @param array $params
     *
     * @return $this
     */
    public function params(array $params, $method='GET')
    {
        foreach($params as $name => $val)
        {
            $this->param($name, $val, $method);
        }

        return $this;
    }

    public function send()
    {
        $options = [
            CURLOPT_URL => $this->getUrlWithParams(),
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => $this->buildRequestHeader(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ];

        if ( ! empty($this->env->getPostParams())) {
            $postParams = $this->env->getPostParams();

            $options[CURLOPT_POST] = count($postParams);
            $options[CURLOPT_POSTFIELDS] = $this->paramsToString($postParams);
        }

        $ch = curl_init();

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    /**
     * Build the request header
     *
     * @return array
     */
    protected function buildRequestHeader()
    {
        return [
            'Authorization: OAuth ' . $this->oAuthParamsToString(),
            'Expect:'
        ];
    }

    /**
     * Get the OAuth parameters are a string
     *
     * @return string
     */
    protected function oAuthParamsToString()
    {
        $params = array_merge($this->oAuthParams(), ['oauth_signature' => $this->generateOAuthSignature()]);
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
    protected function oAuthParams()
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
        $method = strtoupper($this->method);

        $url = rawurlencode($this->requestUrl);

        return $method . '&' . $url . '&' . $this->generateParamString();
    }

    /**
     * Generate a url encoded request string containing the parameters.
     *
     * @return string
     */
    protected function generateParamString()
    {
        $params = array_merge($this->getParams, $this->postParams, $this->oAuthParams());

        $params = $this->paramsToString($params);

        return rawurlencode($params);
    }

    /**
     * Convert param array into a param string
     *
     * @param array $params
     *
     * @return array
     */
    protected function paramsToString(array $params)
    {
        $paramString = '';
        ksort($params);

        foreach ($params as $key => $val) {
            $paramString .= '&' . $key . '=' . rawurlencode($val);
        }

        return trim($paramString, '&');
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

    /** 
     * Get the request url along with the params in an HTTP ready format
     *
     * @return string
     */
    protected function getUrlWithParams()
    {
        $params = $this->paramsToString($this->getParams);

        if ( ! empty($params)) $params = '?' . $params;

        return $this->requestUrl .  $params;
    }


    public function __call($method, $args)
    {
        if ( ! method_exists($this, $method) ) return call_user_func_array([$this->env, $method], $args);

        return call_user_func_array([$this, $method], $args);
    }

}
