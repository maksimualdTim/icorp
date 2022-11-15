<?php

namespace App\services;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
class AmoApiService
{
    private ApiService $api;

    public function __construct(array $config)
    {
        $this->api = new ApiService($config['clientId'], $config['clientSecret'], $config['redirectUri']);
    }

    public function getUserInfo(): ResourceOwnerInterface
    {
        return $this->api->provider->getResourceOwner($this->api->accessToken);
    }

    public function getLeads()
    {

    }

}