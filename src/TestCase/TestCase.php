<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\TestCase;

use Slince\Mechanic\Mechanic;
use Slince\Cache\ArrayCache;
use Slince\Mechanic\Report\TestCaseReport;
use Slince\Mechanic\TestSuite;

class TestCase
{
    /**
     * @var Mechanic
     */
    protected $mechanic;

    /**
     * @var TestSuite
     */
    protected $testSuite;

    /**
     * @var TestCaseReport
     */
    protected $testCaseReport;

    function __construct(Mechanic $mechanic)
    {
        $this->mechanic = $mechanic;
        $this->testCaseReport = $this->createReport();
    }

    /**
     * 获取全局参数
     * @return ArrayCache
     */
    function getGlobalParameters()
    {
        return $this->mechanic->getParameters();
    }

    /**
     * @return Mechanic
     */
    public function getMechanic()
    {
        return $this->mechanic;
    }

    /**
     * @return TestCaseReport
     */
    public function getTestCaseReport()
    {
        return $this->testCaseReport;
    }

    /**
     * @param TestSuite $testSuite
     */
    public function setTestSuite($testSuite)
    {
        $this->testSuite = $testSuite;
    }

    /**
     * @return TestSuite
     */
    public function getTestSuite()
    {
        return $this->testSuite;
    }
    
    /**
     * 创建测试报告
     * @return TestCaseReport
     */
    protected function createReport()
    {
        return new TestCaseReport();
    }
}