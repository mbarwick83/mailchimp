<?php

namespace Mbarwick83\Mailchimp;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Mailchimp
{
    const API_HOST = 'https://<$dc>.api.mailchimp.com/3.0/';
    const LOGIN_HOST = 'https://login.mailchimp.com/';
    const TIMEOUT = 4.0;

    protected $client;
    protected $client_id;
    protected $client_secret;

    public function __construct()
    {
        $this->client_id = config('mailchimp.client_id');
        $this->client_secret = config('mailchimp.client_secret');

    	$this->client = new Client([
    	    'base_uri' => self::API_HOST,
    	    'timeout'  => self::TIMEOUT,
    	]);	
    }

    public function test()
    {
        return "works dude";
    }

    // /**
    // * Get authorization url for oauth
    // * 
    // * @return String
    // */
    // public function getLoginUrl()
    // {
	   // return $this->url('oauth2/authorize', self::LOGIN_HOST);
    // }

    // /**
    // * Get user's access token and basic info
    // * 
    // * @param string $code
    // */
    // public function getAccessToken($code)
    // {
	   // return $this->post('1/oauth2/token.json', true, ['code' => $code]);
    // }

    // /**
    //  * Get user details from access token
    //  */
    // public function getUserDetails($access_token)
    // {
    //     $account = $this->get('1/user.json', ['access_token' => $access_token]);
    //     return array_merge($account, ['access_token' => $access_token]);
    // }

    // /**
    // * Make URLs for user browser navigation.
    // *
    // * @param string $path
    // * @param string $host [base url]
    // * @param array  $parameters
    // *
    // * @return string
    // */
    // protected function url($path, $host, array $parameters = null)
    // {
    // 	$query = [
    //         'client_id' => $this->client_id,
    // 	    'redirect_uri' => $this->redirect_uri,
    // 	    'response_type' => 'code'
    // 	];

    //     if ($parameters)
    //         $query = array_merge($query, $parameters);

    //     $query = http_build_query($query);

    //     return sprintf('%s%s?%s', $host, $path, $query);
    // }

    // *
    // * Make POST calls to the API
    // * 
    // * @param  string  $path          
    // * @param  boolean $authorization [Use access token query params]
    // * @param  array   $parameters    [Optional query parameters]
    // * @return Array
    
    // public function post($path, $authorization = false, array $parameters)
    // {
    // 	$query = [];

    // 	if ($authorization)
    // 	    $query = [
    // 	        'client_id' => $this->client_id,
    // 	    	'client_secret' => $this->client_secret,
    // 	    	'redirect_uri' => $this->redirect_uri,			 
    // 	    	'grant_type' => 'authorization_code',
    // 	    ];

    // 	if ($parameters)
    //         $query = array_merge($query, $parameters);

    //     try {
    // 	    $response = $this->client->request('POST', $path, [
    // 	        'form_params' => $query
    // 	    ]);

    //         return $this->toArray($response);
    // 	} 
    // 	catch (ClientException $e) {
    // 	    return $this->toArray($e->getResponse());
    //     }    	
    // }

    // /**
    // * Make GET calls to the API
    // * 
    // * @param  string $path
    // * @param  array  $parameters [Query parameters]
    // * @return Array
    // */
    // public function get($path, array $parameters)
    // {
    //     try {
    // 	    $response = $this->client->request('GET', $path, [
    // 	        'query' => $parameters
    // 	    ]);

    //         return $this->toArray($response);
    // 	}
    // 	catch (ClientException $e) {
    // 	    return $this->toArray($e->getResponse());
    // 	}
    // }

    // /**
    // * Convert API response to array
    // * 
    // * @param  Object $response
    // * @return Array
    // */
    // protected function toArray($response)
    // {
    // 	return json_decode($response->getBody()->getContents(), true);
    // }
}




