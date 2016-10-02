<?php

namespace MT\DataAPI\Client;

use MT\DataAPI\Client\Endpoint;

class EndpointManager
{
    public static $defaultApiVersion = 3;
    public static $listEndpointsArray = array(
    'id' => 'list_endpoints',
    'route' => '/endpoints',
    'version' => 1,
    'verb' => 'GET',
    );

    public $endpoints;
    public $accessToken;

    private $apiVersion;
    private $baseUrl;
    private $clientId;

    public function __construct($opts)
    {
        if (!is_array($opts) || $opts === array_values($opts)) {
            $this->parameterShouldBeHash();
        }
        $this->initializeParameters($opts);
        if (!$this->baseUrl || !$this->clientId) {
            $this->invalidParameter();
        }
        Endpoint::$apiUrl = $this->apiUrl();
    }

    public function findEndpoint($endpointId)
    {
        $assocArray = $this->findEndpointHash($endpointId);
        return $assocArray ? new Endpoint($assocArray) : null;
    }

    private function initializeParameters($opts)
    {
        $this->baseUrl = $opts['base_url'];
        $this->clientId = $opts['client_id'];
        $this->apiVersion = isset($opts['api_version'])
            ? $opts['api_version'] : self::$defaultApiVersion;
        if (isset($opts['endpoints'])) {
            $this->endpoints = $opts['endpoints'];
        }
        if (isset($opts['accessToken'])) {
            $this->accessToken = $opts['accessToken'];
        }
    }

    private function parameterShouldBeHash()
    {
        throw new Exception('paramter should be hash');
    }

    private function invalidParameter()
    {
        throw new Exception('paramter "base_name" and "client_id" are required');
    }

    private function findEndpointHash($endpointId)
    {
        if (!isset($this->endpoints)) {
            $this->endpoints = $this->retrieveEndpoints();
        }
        $endpoints = array();
        foreach ($this->endpoints as $ep) {
            if ($ep['id'] === $endpointId && $this->apiVersion >= $ep['version']) {
                $endpoints[] = $ep;
            }
        }
        usort($endpoints, function ($left, $right) {
            if ($left['version'] === $right['version']) {
                return 0;
            }
            return ($left['version'] > $right['version']) ? -1 : 1;
        });
        if (empty($endpoints)) {
            return null;
        }
        return $endpoints[0];
    }

    private function apiUrl()
    {
        $url = $this->baseUrl;
        if (substr($url, -1) !== '/') {
            $url .= '/';
        }
        return $url . 'v' . $this->apiVersion;
    }

    private function retrieveEndpoints()
    {
        $endpoint = new Endpoint(self::$listEndpointsArray);
        $res = $endpoint->call();
        if (isset($res['error'])) {
            throw new Exception($res['error']);
        }
        return $res['items'];
    }
}
