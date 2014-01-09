<?php

class ApiTests extends PHPUnit_Framework_TestCase {

    public function testSettingMethodAndParams()
    {
        $settings = [
                'consumer_key' => '',
                'consumer_secret' => '',
                'oauth_token' => '',
                'oauth_token_secret' => '',
            ];

        $config = new Digia\Twitter\Config($settings);
        $env = new Digia\Twitter\Environment;
        $oAuth = new Digia\Twitter\oAuthBuilder;
        $twitter = new Digia\Twitter\Api($config, $env, $oAuth);

        $response = $twitter->get('statuses/user_timeline')
            ->param('screen_name', 'mooredigia')
            ->param('count', 5)
            ->param('exclude_replies', true);

        $this->assertEquals('https://api.twitter.com/1.1/statuses/user_timeline.json', $response->getRequestUrl());
        $this->assertEquals(3, count($response->getGetParams()));
    }

}
