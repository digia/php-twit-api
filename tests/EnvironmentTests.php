<?php

class EnvironmentTests extends PHPUnit_Framework_TestCase {

    public function testSettersAndGetters()
    {
        $env = new Digia\Twitter\Environment;
        
        $env->setMethod('POST');
        $env->setRequestUrl('request/url');
        $env->setGetParam('getTest', 'passing');
        $env->setPostParam('postTest', 'passing');

        $method = $env->getMethod();
        $apiUrl = $env->getApiUrl();
        $requestUrl = $env->getRequestUrl();
        $getGetParams = $env->getGetParams();
        $getPostParams = $env->getPostParams();

        $this->assertEquals('POST', $method);
        $this->assertEquals('https://api.twitter.com/1.1/', $apiUrl);
        $this->assertEquals('request/url', $requestUrl);
        $this->assertEquals('passing', $getGetParams['getTest']);
        $this->assertEquals('passing', $getPostParams['postTest']);
    }

}
