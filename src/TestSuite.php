<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace slince\Mechanic;

use Cake\Utility\Text;
use Slince\Mechanic\Report\TestSuiteReport;
use slince\Mechanic\TestCase\TestCase;

abstract class TestSuite
{
    /**
     * 测试套件名称
     * @var string
     */
    protected $name;

    /**
     * @var TestCase[]
     */
    protected $testCases;

    /**
     * @var TestSuiteReport
     */
    protected $testSuiteReport;

    function __construct()
    {
        $this->testSuiteReport = new TestSuiteReport($this);
    }

    /**
     * 测试套件声明
     * @return mixed
     */
    abstract function suite();

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
            $this->name = Text::uuid();
        }
        return $this->name;
    }

    /**
     * @param TestCase $testCase
     */
    function addTestCase(TestCase $testCase)
    {
        $this->testCases[] = $testCase;
    }

    /**
     * @return TestCase[]
     */
    public function getTestCases()
    {
        return $this->testCases;
    }

    /**
     * @return TestSuiteReport
     */
    public function getTestSuiteReport()
    {
        return $this->testSuiteReport;
    }
}