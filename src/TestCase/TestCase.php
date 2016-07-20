<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace slince\Mechanic\TestCase;

use slince\Mechanic\Mechanic;
use Slince\Cache\ArrayCache;
use Slince\Mechanic\Report\TestCaseReport;

class TestCase
{
    /**
     * @var Mechanic
     */
    protected $mechanic;

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
     * sheng
     * @return TestCaseReport
     */
    protected function createReport()
    {
        return new TestCaseReport();
    }
}