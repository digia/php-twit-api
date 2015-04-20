<?php 

namespace Digia\Twitter;

class Environment 
{
    /**
     * Twitter's v1.1 API base url
     *
     * @var string
     */
    protected $apiUrl = 'https://api.twitter.com/1.1/';

    /**
     * HTTP method
     *
     * @var string
     */
    protected $method = 'GET';

    /**
     * Twitter request URL
     *
     * @var string
     */
    protected $requestUrl = null;

    /**
     * Twitter API GET params
     *
     * @var array
     */
    protected $getParams = [];

    /**
     * Twitter API POST params
     *
     * @var array
     */
    protected $postParams = [];


    /**
     * Set the HTTP method
     *
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * Set the api request "section"
     *
     * @param string $url
     */
    public function setRequestUrl($url)
    {
        $this->requestUrl = $url;
    }

    /**
     * Set|Add to the GET parameters
     *
     * @param string $key
     * @param string $val
     */
    public function setGetParam($key, $val)
    {
        $this->getParams[$key] = $val;
    }

    /**
     * Set|Add to the POST parameters
     *
     * @param string $key
     * @param string $val
     */
    public function setPostParam($key, $val)
    {
        $this->postParams[$key] = $val;
    }

    /**
     * Get the Twitter's api url
     *
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Get the API request url
     *
     * @return string
     */
    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    /**
     * Get the HTTP method
     *
     * @return string
     */
    public function getMethod()
    {
        return strtoupper($this->method);
    }

    /**
     * Get the GET parameters
     *
     * @return array
     *
     * @TODO This name should burn in a fire
     */
    public function getGetParams()
    {
        return $this->getParams;
    }

    /**
     * Get the POST parameters
     *
     * @return array
     */
    public function getPostParams()
    {
        return $this->postParams;
    }

    /**
     * Convert param array into a param string
     *
     * @param array $params
     *
     * @return array
     */
    public function paramsToString(array $params)
    {
        $paramString = '';
        ksort($params);

        foreach ($params as $key => $val) {
            $paramString .= '&' . $key . '=' . rawurlencode($val);
        }

        return trim($paramString, '&');
    }

    /** 
     * Get the request url along with the params in an HTTP ready format
     *
     * @return string
     */
    public function getUrlWithParams()
    {
        $params = $this->paramsToString($this->getParams);

        if ( ! empty($params)) $params = '?' . $params;

        return $this->requestUrl .  $params;
    }

}
