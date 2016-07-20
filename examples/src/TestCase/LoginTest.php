<?php
namespace Slince\Example\TestCase;

use Slince\Mechanic\TestCase\ApiTestCase;
use Webmozart\Assert\Assert;

class LoginTest extends ApiTestCase
{
    protected $url = 'http://app.shein.com/index.php?model=login_register_ajax&action=mobile_login';

    function testSuccessLogin()
    {
        $api = $this->createApi($this->url)
            ->setQuery([
                'email' => 'test@test.cn',
                'password' => '123456'
            ])
            ->setProxy('tcp://127.0.0.1:8888');
        $response = $this->request($api);
        $responseData = json_decode($response);
        Assert::eq($response->getStatusCode(), 200, '服务器连接错误');
        Assert::eq($responseData->code, 0, '状态码返回错误');
    }

    function testUserNotExists()
    {
        $api = $this->createApi($this->url)
            ->setQuery([
                'email' => 'user@notexists.com',
                'password' => '123456'
            ]);
        $response = $this->request($api);
        $responseData = json_decode($response);
        Assert::eq($response->getStatusCode(), 200, '服务器连接错误');
        Assert::eq($responseData->code, 104, '状态码返回错误');
    }
}