<?php

namespace MT\DataAPI;

use MT\DataAPI\Client\EndpointManager;

class Client
{
    const VERION = '0.0.1';

    public $accessToken;

    private $endpointManager;

    public function __construct($opts)
    {
        if (isset($opts['accessToken'])) {
            $this->accessToken = $opts['accessToken'];
            unset($opts['accessToken']);
        }
        $this->endpointManager = new EndpointManager($opts);
    }

    public function call($endpointId, $args = array())
    {
        $endpoint = $this->endpointManager->findEndpoint($endpointId);
        if (!$endpoint) {
            return null;
        }
        $res = $endpoint->call($this->accessToken, $args);
        switch ($endpointId) {
            case 'authenticate':
                $this->accessToken = $res['accessToken'];
                break;
            case 'list_endpoints':
                $this->endpointManager->endpoints = $res['items'];
                break;
        }
        return $res;
    }

    public function endpoints()
    {
        return $this->endpointManager->endpoints;
    }
}
