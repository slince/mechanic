<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace slince\Mechanic\TestCase;

use GuzzleHttp\Client;
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
     * 创建api
     * @return Api
     */
    function createApi()
    {
        return $this->getMechanic()->getContainer()->create('Slince\\Mechanic\\Api', func_get_args());
    }

    /**
     * 请求api
     * @param Api $api
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    function request(Api $api)
    {
        $request = static::$requestAdapter->makeRequest($api);
        $options = static::$requestAdapter->getOptions($api);
        return static::getHttpClient()->send($request, $options);
    }
}