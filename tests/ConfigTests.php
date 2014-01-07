<?php 

class ConfigTests extends PHPUnit_Framework_TestCase {

    public function testSettingConfiguration()
    {
        $settings = [
            'consumer_key' => '',
            'consumer_secret' => '',
            'oauth_token' => '', 
            'oauth_token_secret' => '',
            ];

        $config = new Digia\Twitter\Config($settings);

        $this->assertEquals($settings['consumer_key'], $config->consumerKey);
        $this->assertEquals($settings['consumer_secret'], $config->consumerKey);
        $this->assertEquals($settings['oauth_token'], $config->consumerKey);
        $this->assertEquals($settings['oauth_token'], $config->consumerKey);
    }

    public function testNonExistantSettingException()
    {
        $this->setExpectedException('Digia\Twitter\Exception\NonExistantSetting');

        $settings = [
            'consumer_key' => '',
            'consumer_secret' => '',
            'oauth_token' => '', 
            'oauth_token_secret' => '',
            ];

        $config = new Digia\Twitter\Config($settings);

        $config->thisDoesntExist;
    }

}
