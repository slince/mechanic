<?php
namespace Slince\Example\TestSuite;

use Slince\Example\TestCase\LoginTest;
use Slince\Mechanic\TestSuite;

class UserTestSuite extends TestSuite
{
    function suite()
    {
        //设置测试套件名称，如果不提供会默认生成一个UUID名称
        $this->setName('UserTestSuite');
        //添加测试用例
        $this->addTestCase(new LoginTest());
    }
}