<?php
namespace Slince\Example\TestCase;

use Slince\Mechanic\TestCase\ApiTestCase;
use Webmozart\Assert\Assert;

class LoginTest extends ApiTestCase
{
    protected $url = 'https://app.shein.com/index.php?model=login_register_ajax&action=mobile_login';

    /**
     * 测试成功登陆的情况
     */
    function testSuccessLogin()
    {
        $api = $this->createApi($this->url)
            ->setQuery([
                'email' => 'test@test.cn',
                'password' => '123456'
            ])
            ->setVerify($this->getMechanic()->getAssetPath() . DIRECTORY_SEPARATOR  . 'cacert.pem');
        $response = $this->request($api);
        $this->getTestCaseReport()->addMessage("备注");
        $responseData = json_decode($response->getBody());
        Assert::eq($response->getStatusCode(), 200, '服务器连接错误');
        Assert::eq($responseData->code, 0, '状态码返回错误');
    }

    /**
     * 测试用户不存在
     */
    function testUserNotExists()
    {
        $api = $this->createApi($this->url)
            ->setQuery([
                'email' => 'user@notexists.com',
                'password' => '123456'
            ])
            ->setVerify($this->getMechanic()->getAssetPath() . DIRECTORY_SEPARATOR  . 'cacert.pem');
        $response = $this->request($api);
        $responseData = json_decode($response->getBody());
        Assert::eq($response->getStatusCode(), 200, '服务器连接错误');
        Assert::eq($responseData->code, 104, '状态码返回错误');
    }
}