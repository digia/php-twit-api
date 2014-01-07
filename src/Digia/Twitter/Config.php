<?php

namespace Digia\Twitter;

use Digia\Twitter\Exception\NonExistantSetting;

class Config {
    
    protected $consumerKey = null;
    protected $consumerSecret = null;
    protected $oAuthToken = null;
    protected $oAuthTokenSecret = null;

    public function __construct(array $settings)
    {
        $this->consumerKey = $settings['consumer_key'];
        $this->consumerSecret = $settings['consumer_secret'];
        $this->oAuthToken = $settings['oauth_token'];
        $this->oAuthTokenSecret = $settings['oauth_token_secret'];
    }

    public function __get($var)
    {
        if(isset($this->{$var})) return $this->{$var};

        throw new NonExistantSetting("The setting {$var} doesn't exist.");
    }

}
