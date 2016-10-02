<?php

namespace MT\DataAPI\Tests;

use MT\DataAPI\Client;
use MT\DataAPI\Client\EndpointManager;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public static $opts = array(
        'base_url' => 'http://localhost/mt/mt-data-api.cgi',
        'client_id' => 'test'
    );

    public function testConstruct()
    {
        $client = new Client(self::$opts);
        $this->assertInstanceOf('MT\DataAPI\Client', $client);
    }

    public function testCall()
    {
    }

    public function testEndpointsWithoutEndpoints()
    {
        $client = new Client(self::$opts);
        $this->assertNull($client->endpoints());
    }

    public function testEndpointsWithEndpoints()
    {
        $endpoints = array( EndpointManager::$listEndpointsArray);
        $optsWithEndpoints = array_merge(self::$opts, array(
            'endpoints' => $endpoints
        ));
        $client = new Client($optsWithEndpoints);
        $this->assertEquals($endpoints, $client->endpoints());
    }
}
