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
        $this->redirect_uri = config('mailchimp.redirect_uri');
    }

    /**
     * Create client instance
     * 
     * @param   [string] $base_uri
     * @return  Response
     */
    protected function client($base_uri, $data_center = false)
    {
        return new Client([
            'base_uri' => $data_center ? str_replace('<$dc>', $data_center, $base_uri) : $base_uri,
            'timeout'  => self::TIMEOUT,
        ]);     
    }

    /**
    * Get authorization url for oauth
    * 
    * @return   String
    */
    public function getLoginUrl()
    {
	   return $this->url('oauth2/authorize', self::LOGIN_HOST);
    }

    /**
    * Get user's access token
    * 
    * @param    string $code 
    * @return   Response
    */
    public function getAccessToken($code)
    {
	   $response = $this->post('oauth2/token', false, false, ['code' => $code], true);
       return $response['access_token'];
    }

    /**
     * Get user details from access token
     *
     * @param   (string) $access_token
     * @return  Response
     */
    public function getAccountDetails($access_token)
    {
        $meta = $this->toArray($this->client(self::LOGIN_HOST)->request('GET', 'oauth2/metadata', [
            'headers' => ["Authorization" => "OAuth $access_token"]
        ]));

        $meta = array('dc' => $meta['dc'], 'role' => $meta['role'], 'access_token' => $access_token);
        $account_details = $this->get(null, $access_token, $meta['dc'], ['exclude_fields' => '_links']);

        return array_merge($meta, $account_details);
    }

    /**
    * Make URLs for user browser navigation.
    *
    * @param    string $path
    * @param    string $host [base url]
    * @param    array  $parameters
    * @return   Response
    */
    protected function url($path, $host, array $parameters = null)
    {
    	$query = [
            'client_id' => $this->client_id,
    	    'client_secret' => $this->client_secret,
    	    'response_type' => 'code'
    	];

        if ($parameters)
            $query = array_merge($query, $parameters);

        $query = http_build_query($query);

        return sprintf('%s%s?%s', $host, $path, $query);
    }

    /**
    * Make POST calls to the API
    * 
    * @param    string  $path
    * @param    string  $access_token  
    * @param    string  $data_center      [user's data center code]
    * @param    array   $parameters       [Optional query parameters]        
    * @param    boolean $authorization    [Use access token query params] 
    * @return   Response
    */    
    public function post($path, $access_token = false, $data_center = false, array $parameters = null, $authorization = false)
    {
    	$query = [];

    	if ($authorization)
    	    $query = [
    	        'client_id' => $this->client_id,
    	    	'client_secret' => $this->client_secret,			 
    	    	'grant_type' => 'authorization_code',
                'redirect_uri' => $this->redirect_uri
    	    ];       

    	if ($parameters)
            $query = array_merge($query, $parameters);

        try {
            $client = $this->client(($authorization) ? self::LOGIN_HOST : self::API_HOST, $data_center);

            if ($access_token)
            {
                $response = $client->request('POST', $path, [
                    'auth' => [null, $access_token],
                    'json' => $query
                ]); 
            }
            else {
                $response = $client->request('POST', $path, [
                    'form_params' => $query
                ]);
            } 	    

            return $this->toArray($response);
    	} 
    	catch (ClientException $e) {
    	    return $this->toArray($e->getResponse());
        }    	
    }

    /**
    * Make GET calls to the API
    * 
    * @param    string $path
    * @param    string $access_token
    * @param    string $data_center  [user's data center code]
    * @param    array  $parameters   [Query parameters]
    * @return   Response
    */
    public function get($path, $access_token, $data_center, array $parameters = null)
    {
        try {
            $client = $this->client(self::API_HOST, $data_center);
    	    $response = $client->request('GET', $path, [
    	        'auth' => [null, $access_token],
                'query' => $parameters
    	    ]);

            return $this->toArray($response);
    	}
    	catch (ClientException $e) {
    	    return $this->toArray($e->getResponse());
    	}
    }

    /**
    * Make DELETE calls to the API
    * 
    * @param    string  $path
    * @param    string  $access_token
    * @param    string  $data_center [user's data center code]
    * @param    array   $parameters  [Optional query parameters]
    * @return   Response
    */
    public function delete($path, $access_token, $data_center, array $parameters)
    {
        try {
            $client = $this->client(self::API_HOST, $data_center);
            $response = $client->request('DELETE', $path, [
                'query' => $parameters
            ]);

            return $this->toArray($response);
        }
        catch (ClientException $e) {
            return $this->toArray($e->getResponse());
        } 
    }

    /**
    * Convert API response to array
    * 
    * @param    Object $response
    * @return   Response
    */
    protected function toArray($response)
    {
    	return json_decode($response->getBody()->getContents(), true);
    }    
}




