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
    const TEST_SUITE_EXECUTE = 'testSuite.execute';

    /**
     * 测试项开始执行测试结束事件
     * @var string
     */
    const TEST_SUITE_EXECUTED = 'testSuite.executed';

    /**
     * 测试项开始执行测试事件
     * @var string
     */
    const TEST_CASE_EXECUTE = 'testCase.execute';

    /**
     * 测试项开始执行测试结束事件
     * @var string
     */
    const TEST_CASE_EXECUTED = 'testCase.executed';

}