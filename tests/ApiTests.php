<?php

class ApiTests extends PHPUnit_Framework_TestCase {

    public function test()
    {
        $settings = [
                'consumer_key' => 'aPRdmcwNTiqEg5WPzk10w',
                'consumer_secret' => 'As4TcGatCyUt0fmoJlT8RnO0YshI6qr5eVGSbyNrF4',
                'oauth_token' => '448573630-3KfPPIBxBg4M2dplTaGWeIuovlcQBnjQKT3Ee5Cm',
                'oauth_token_secret' => 'TEnp3RvPEq5p5SZMyDYrP9WDn8wfn2dlemcKQw0Gp3lNm',
            ];

        $config = new Digia\Twitter\Config($settings);
        $twitter = new Digia\Twitter\Api($config);

        $response = $twitter->get('statuses/user_timeline')
            ->param('screen_name', 'mooredigia')
            ->param('count', 5)
            ->param('exclude_replies', true);

        $this->assertEquals('https://api.twitter.com/1.1/statuses/user_timeline.json', $response->requestUrl);
        $this->assertEquals(3, count($response->getParams));
    }

}
