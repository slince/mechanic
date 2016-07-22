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
     * 测试用例名称
     * @var string
     */
    protected $name;
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

    function __construct(Mechanic $mechanic = null)
    {
        $this->mechanic = $mechanic;
        $this->testCaseReport = $this->createReport();
        $this->initialize();
    }

    function initialize()
    {

    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        if (empty($this->name)) {
            $this->name = basename(get_class($this));
        }
        return $this->name;
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
     * @param Mechanic $mechanic
     */
    public function setMechanic($mechanic)
    {
        $this->mechanic = $mechanic;
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