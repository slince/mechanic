<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\Report;

use Slince\Mechanic\TestCase\TestCase;

class TestCaseReport implements ReportInterface
{
    /**
     * @var TestCase
     */
    protected $testCase;

    /**
     * @var TestMethodReport[]
     */
    protected $testMethodReports = [];

    /**
     * message
     * @var array
     */
    protected $messages = [];

    /**
     * @var TestSuiteReport
     */
    protected $testSuiteReport;

    function __construct(TestCase $testCase = null)
    {
        $this->testCase = $testCase;
    }

    /**
     * @return TestCase
     */
    public function getTestCase()
    {
        return $this->testCase;
    }

    /**
     * @param TestCase $testCase
     */
    public function setTestCase($testCase)
    {
        $this->testCase = $testCase;
    }

    /**
     * @param TestSuiteReport $testSuiteReport
     */
    public function setTestSuiteReport($testSuiteReport)
    {
        $this->testSuiteReport = $testSuiteReport;
    }

    /**
     * @return TestSuiteReport
     */
    public function getTestSuiteReport()
    {
        return $this->testSuiteReport;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param array $messages
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
    }

    /**
     * 写入测试用例方法提示
     * @param $name
     * @param $message
     */
    function setMessage($name, $message)
    {
        $this->messages[$name] = $message;
    }
    /**
     * 添加一个message
     * @param $message
     */
    function addMessage($message)
    {
        $this->messages[] = $message;
    }

    /**
     * @return TestMethodReport[]
     */
    public function getTestMethodReports()
    {
        return $this->testMethodReports;
    }

    /**
     * @param TestMethodReport[] $testMethodReports
     */
    public function setTestMethodReports($testMethodReports)
    {
        $this->testMethodReports = $testMethodReports;
    }

    /**
     * @param TestMethodReport $testMethodReport
     */
    public function addTestMethodReport($testMethodReport)
    {
        $testMethodReport->setTestCaseReport($this);
        $this->testMethodReports[] = $testMethodReport;
    }

    /**
     * @return bool
     */
    public function getTestResult()
    {
        foreach ($this->getTestMethodReports() as $testMethodReport) {
            if (!$testMethodReport->getTestResult()) {
                return false;
            }
        }
        return true;
    }

    /**
     * 获取成功的测试方法报告
     * @return TestMethodReport[]
     */
    function getSuccessTestMethodReports()
    {
        return array_filter($this->getTestMethodReports(), function(TestMethodReport $testMethodReport){
            return $testMethodReport->getTestResult();
        });
    }

    /**
     * 获取测试失败的测试方法报告
     * @return TestMethodReport[]
     */
    function getFailedTestMethodReports()
    {
        return array_filter($this->getTestMethodReports(), function(TestMethodReport $testMethodReport){
            return !$testMethodReport->getTestResult();
        });
    }

    /**
     * 分析报告
     * @return array
     */
    function analyze()
    {
        return [
            'result' => $this->getTestResult(),
            'testMethodNum' => count($this->getTestMethodReports()),
            'testMethodSuccessNum' => count($this->getSuccessTestMethodReports()),
            'testMethodFailedNum' => count($this->getFailedTestMethodReports()),
            'testMethodAnalysis' => array_map(function(TestMethodReport $testMethodReport){
                return $testMethodReport->analyze();
            }, $this->getTestMethodReports())
        ];
    }
}