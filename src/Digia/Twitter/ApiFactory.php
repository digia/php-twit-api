<?php

namespace Digia\Twitter;


class ApiFactory 
{
    /**
     * Config
     *
     * @var Digia/Twitter/Config
     */
    protected $config;

    /**
     * Environment
     *
     * Holds/understands how to interact with the "current" Twitter api environment
     *
     * @var Digia/Twitter/Environment
     */
    protected $environment;

    /**
     * oAuth Builder
     *
     * Builds the oAuth information needed to make requests to Twitter
     *
     * @var Digia/Twitter/OAuthBuilder
     */
    protected $OAuthBuilder;

    public function __construct($config)
    {
        if (is_array($config)) {
            $config = new Config($config);
        }

        $this->config = $config;
        $this->environment = new Environment();
        $this->OAuthBuilder = new OAuthBuilder();
    }

    public function make()
    {
        return new Api($this->config, $this->environment, $this->OAuthBuilder);
    }
}
