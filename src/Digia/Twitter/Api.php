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
     * API request environment 
     *
     * @var Digia\Twitter\Environment
     */
    protected $env;

    /**
     * OAuth Builder
     *
     * @var Digia\Twitter\OAuthBuilder
     */
    protected $oAuth;


    public function __construct(Config $config, Environment $env, OAuthBuilder $oAuth)
    {
        $this->config = $config;
        $this->env = $env;
        $this->oAuth = $oAuth;
    }

    /**
     * Set the HTTP method to GET and add the url 
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
     * Set the HTTP method to POST and add the api url
     *
     * @param string $api
     *
     * @return this
     */
    public function post($api)
    {
        $this->env->setMethod('POST');

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
        $config = $this->config;
        $env = $this->env;

        $options = [
            CURLOPT_URL => $this->getUrlWithParams(),
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => $this->oAuth->createHeader($config, $env),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ];

        if ( ! empty($this->env->getPostParams())) {
            $postParams = $this->env->getPostParams();

            $options[CURLOPT_POST] = count($postParams);
            $options[CURLOPT_POSTFIELDS] = $this->env->paramsToString($postParams);
        }

        $ch = curl_init();

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    public function __call($method, $args)
    {
        if ( ! method_exists($this, $method) ) return call_user_func_array([$this->env, $method], $args);

        return call_user_func_array([$this, $method], $args);
    }

}
