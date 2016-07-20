<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\Report;

use Slince\Mechanic\TestSuite;

class TestSuiteReport implements ReportInterface
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

    /**
     * @var Report
     */
    protected $report;

    /**
     * 测试结果
     * @var boolean
     */
    protected $result;

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

    /**
     * @param Report $report
     */
    public function setReport($report)
    {
        $this->report = $report;
    }

    /**
     * @return Report
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * 追加测试用例报告
     * @param TestCaseReport $testCaseReport
     */
    function addTestCaseReport(TestCaseReport $testCaseReport)
    {
        $testCaseReport->setTestSuiteReport($this);
        $this->testCaseReports[] = $testCaseReport;
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

    /**
     * 是否重新计算
     * @param bool $refresh
     * @return bool
     */
    public function getTestResult($refresh = false)
    {
        if (is_null($this->result) || $refresh) {
            $result = true;
            foreach ($this->getTestCaseReports() as $testCaseReport) {
                if (!$testCaseReport->getTestResult()) {
                    $result = false;
                    break;
                }
            }
            $this->result = $result;
        }
        return $this->result;
    }

    /**
     * 获取成功的测试用例报告
     * @return TestCaseReport[]
     */
    function getSuccessTestCaseReports()
    {
        return array_filter($this->getTestCaseReports(), function(TestCaseReport $testCaseReport){
            return $testCaseReport->getTestResult();
        });
    }

    /**
     * 获取测试失败的测试用例报告
     * @return TestCaseReport[]
     */
    function getFailedTestCaseReports()
    {
        return array_filter($this->getTestCaseReports(), function(TestCaseReport $testCaseReport){
            return !$testCaseReport->getTestResult();
        });
    }

    /**
     * 分析报告
     * @return array
     */
    function analyze()
    {
        return [
            'name' => $this->getTestSuite()->getName(),
            'result' => $this->getTestResult(true),
            'testCaseNum' => count($this->getTestCaseReports()),
            'testCaseSuccessNum' => count($this->getSuccessTestCaseReports()),
            'testCaseFailedNum' => count($this->getFailedTestCaseReports())
        ];
    }
}