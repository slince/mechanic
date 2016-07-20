<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\Report;

use slince\Mechanic\TestSuite;

class TestSuiteReport
{
    /**
     * @var TestSuite
     */
    protected $testSuite;

    /**
     * 测试用例报告
     * @var TestCaseReport[]
     */
    protected $testCaseReports;

    function __construct(TestSuite $testSuite)
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
     * @param TestSuite $testSuite
     */
    public function setTestSuite(TestSuite $testSuite)
    {
        $this->testSuite = $testSuite;
    }

    function addTestCaseReport(TestCaseReport $testSuiteReport)
    {
        $this->testCaseReports[] = $testSuiteReport;
    }

    /**
     * @param TestCaseReport[] $testCaseReports
     */
    public function setTestCaseReports($testCaseReports)
    {
        $this->testCaseReports = $testCaseReports;
    }

    /**
     * @return TestCaseReport[]
     */
    public function getTestCaseReports()
    {
        return $this->testCaseReports;
    }
}