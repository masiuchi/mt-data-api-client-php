<?php

namespace MT\DataAPI\Tests\Client;

use MT\DataAPI\Client\APIRequest;
use MT\DataAPI\Client\Endpoint;
use MT\DataAPI\Client\EndpointManager;

class APIRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $endpoint = new Endpoint(EndpointManager::$listEndpointsArray);
        $apiRequest = new APIRequest($endpoint);
        $this->assertInstanceOf('MT\DataAPI\Client\APIRequest', $apiRequest);
    }
}
