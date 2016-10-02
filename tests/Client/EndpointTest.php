<?php

namespace MT\DataAPI\Tests\Client;

use MT\DataAPI\Client\Endpoint;
use MT\DataAPI\Client\EndpointManager;

class EndpointTest extends \PHPUnit_Framework_TestCase
{
    const API_URL = 'http://localhost/mt/mt-data-api.cgi/v3';

    private static $listEntriesArray = array(
        'id' => 'list_entries',
        'route' => '/sites/:site_id/entries',
        'version' => 1,
        'verb' => 'GET',
    );

    private static $deleteArray = array(
        'id' => 'delete',
        'route' => '/delete',
        'version' => 1,
        'verb' => 'DELETE',
    );

    public static function setUpBeforeClass()
    {
        Endpoint::$apiUrl = self::API_URL;
    }

    public function testConstruct()
    {
        $endpoint = new Endpoint(EndpointManager::$listEndpointsArray);
        $this->assertInstanceOf('MT\DataAPI\Client\Endpoint', $endpoint);
    }

    /**
     * @expectedException Exception
     */
    public function testContructWithInvalidArgs()
    {
        new Endpoint(1);
    }

    public function testRequestUrl()
    {
        $endpointArray = EndpointManager::$listEndpointsArray;
        $endpoint = new Endpoint($endpointArray);
        $args = array();
        $expected = self::API_URL . $endpointArray['route'];
        $this->assertEquals($expected, $endpoint->requestUrl($args));
    }

    public function testRequestUrlWithParameterRoute()
    {
        $endpoint = new Endpoint(self::$listEntriesArray);
        $args = array('site_id' => 1);
        $expected = self::API_URL . '/sites/1/entries';
        $this->assertEquals($expected, $endpoint->requestUrl($args));
    }

    /**
     * @expectedException Exception
     */
    public function testRequestUrlWithParameterRouteAndNoParamter()
    {
        $endpoint = new Endpoint(self::$listEntriesArray);
        $args = array();
        $endpoint->requestUrl($args);
    }

    public function testRequestUrlWithQueryAndGetMethod()
    {
        $endpointArray = EndpointManager::$listEndpointsArray;
        $endpoint = new Endpoint($endpointArray);
        $args = array('limit' => 1, 'offset' => 2);
        $expected = self::API_URL . $endpointArray['route'] . '?limit=1&offset=2';
        $this->assertEquals($expected, $endpoint->requestUrl($args));
    }

    public function testRequestUrlWithQueryAndNotGetMethod()
    {
        $endpoint = new Endpoint(self::$deleteArray);
        $args = array('limit' => 1, 'offset' => 2);
        $expected = self::API_URL . self::$deleteArray['route'];
        $this->assertEquals($expected, $endpoint->requestUrl($args));
    }
}
