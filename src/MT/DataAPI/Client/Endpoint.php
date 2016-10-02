<?php

namespace MT\DataAPI\Client;

use MT\DataAPI\Client\APIRequest;

class Endpoint
{
    public static $apiUrl = '';

    public $verb;

    private $endpointId;
    private $route;
    private $version;

    public function __construct($args)
    {
        if (!is_array($args) || $args === array_values($args)) {
            throw new \Exception('parameter should be associative array');
        }

        $this->endpointId = $args['id'];
        $this->route = $args['route'];
        $this->version = $args['version'];
        $this->verb = $args['verb'];

        if (!$this->isValidVerb()) {
            throw new Exception("invalid verb: $this->verb");
        }
    }

    public function call($accessToken = null, $args = array())
    {
        $apiRequest = new APIRequest($this);
        $response = $apiRequest->send($accessToken, $args);
        if (!$response) {
            return null;
        }
        return json_decode($response->getBody(), true);
    }

    public function requestUrl($args)
    {
        $url = self::$apiUrl . $this->route($args);
        if ($this->verb === 'GET') {
            $url .= $this->queryString($args);
        }
        return $url;
    }

    private function route(&$args = array())
    {
        $route = $this->route;
        if (preg_match_all('/:[^:\/]+(?=\/|$)/', $route, $matches)) {
            foreach ($matches[0] as $m) {
                $key = preg_replace('/^:/', '', $m);
                if (!isset($args[$key])) {
                    throw new \Exception("parameter \"$key\" is required");
                }
                $value = $args[$key];
                unset($args[$key]);
                $route = preg_replace("/$m/", $value, $route);
            }
        }
        return $route;
    }

    private function queryString($args = array())
    {
        if (empty($args)) {
            return '';
        }
        $keyAndValue = array();
        ksort($args);
        foreach ($args as $key => $value) {
            $keyAndValue[] = $key . '=' . $value;
        }
        return '?' . join('&', $keyAndValue);
    }

    private function isValidVerb()
    {
        return in_array($this->verb, array('GET', 'POST', 'PUT', 'DELETE'));
    }
}
