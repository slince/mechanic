<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic;


class EventStore
{
    /**
     * mechanic启动事件
     * @var string
     */
    const MECHANIC_RUN = 'mechanic.run';

    /**
     * mechanic结束事件
     * @var string
     */
    const MECHANIC_FINISH = 'mechanic.finish';

    /**
     * 测试项开始执行测试事件
     * @var string
     */
    const TESTCASE_EXECUTE = 'testCase.execute';

    /**
     * 测试项开始执行测试结束事件
     * @var string
     */
    const TESTCASE_EXECUTED = 'testCase.executed';

    /**
     * 请求发起之前事件
     * @var string
     */
    const TESTCASE_PRE_REQUEST = 'testCase.preRequest';

    /**
     * 请求发起之后事件
     * @var string
     */
    const TESTCASEAFTER_REQUEST = 'testCase.afterRequest';

}