<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\TestCase;

use GuzzleHttp\Client;
use Slince\Mechanic\Mechanic;
use Slince\Mechanic\TestCase\ApiTest\Api;
use Slince\Mechanic\TestCase\ApiTest\RequestAdapter;

class ApiTestCase extends TestCase
{
    /**
     * @var Client
     */
    static $httpClient;

    /**
     * @var RequestAdapter
     */
    static $requestAdapter;

    /**
     * 获取请求客户端
     * @return Client
     */
    static function getHttpClient()
    {
        if (is_null(static::$httpClient)) {
            static::$httpClient = new Client();
        }
        return static::$httpClient;
    }

    /**
     * 获取请求适配器
     * @return RequestAdapter
     */
    static function getRequestAdapter()
    {
        if (is_null(static::$requestAdapter)) {
            static::$requestAdapter = new RequestAdapter(Mechanic::instance());
        }
        return static::$requestAdapter;
    }

    /**
     * 创建api
     * @return Api
     */
    function createApi()
    {
        return $this->getMechanic()->getContainer()->create('Slince\\Mechanic\\TestCase\\ApiTest\\Api', func_get_args());
    }

    /**
     * 请求api
     * @param Api $api
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    function request(Api $api)
    {
        $request = static::getRequestAdapter()->makeRequest($api);
        $options = static::getRequestAdapter()->getOptions($api);
        return static::getHttpClient()->send($request, $options);
    }
}