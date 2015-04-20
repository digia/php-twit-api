## PHP-Twit-API 

An modern and elegant PHP Library for Twitter API v1.1 with OAuth.

*Work in progress!*
TODO: 
- Clean up
- Finish writing tests

#### Features
- Composer
- Namespace
- Chaining

### Quick Example 
```php 
<?php 
    $twitter = (new ApiFactory(array(
        'consumer_key' => '...',
        'consumer_secret' => '...',
        'oauth_token' => '...',
        'oauth_token_secret' => '...',
        )))->make();

    /**
     * Example: GET request with param chaining
     */
    $response = $twitter->get('statuses/user_timeline')
                ->param('screen_name', 'mooredigia')
                ->param('count', 10)
                ->send();

    var_dump(json_decode($response));

    /**
     * Example: GET with associative array params 
     */
    $params = [
        'screen_name' => 'mooredigia',
        'count' => 10,
        ];
    $response = $twitter->get('statuses/user_timeline')
                ->params($params)
                ->send();

    var_dump(json_decode($response));

    /**
     * Post requests are just as simple...
     */
    $response = $twitter->post('statuses/user_timeline')
                ->param('screen_name', 'mooredigia', 'POST')
                ->param('count', 10, 'POST')
                ->send();

    var_dump(json_decode($response));
```

### REQUIREMENTS 
- PHP VERSION >= 5.4
- PHP CURL 

