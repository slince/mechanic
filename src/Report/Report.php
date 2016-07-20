<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\Report;

class Report
{
    /**
     * 参数
     * @var array
     */
    protected $parameters = [];

    /**
     * 测试套件测试报告
     * @var array
     */
    protected $testSuiteReports = [];

    /**
     * 设置参数
     * @param $name
     * @param $value
     */
    function setParameter($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    /**
     * 读参数
     * @param $name
     * @return mixed|null
     */
    function getParameter($name)
    {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
    }

    /**
     * @param array $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * 获取所有的参数
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return bool
     */
    public function getTestResult()
    {
        foreach ($this->getTestSuiteReports() as $testSuiteReport) {
            if (!$testSuiteReport->getTestResult()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return TestSuiteReport[]
     */
    public function getTestSuiteReports()
    {
        return $this->testSuiteReports;
    }

    /**
     * @param array $testSuiteReports
     */
    public function setTestSuiteReports($testSuiteReports)
    {
        $this->testSuiteReports = $testSuiteReports;
    }

    /**
     * @param TestSuiteReport $testSuiteReport
     */
    public function addTestSuiteReports(TestSuiteReport $testSuiteReport)
    {
        $testSuiteReport->setReport($this);
        $this->testSuiteReports[] = $testSuiteReport;
    }

    /**
     * 返回所有的message
     * @return array
     */
    public function getMessages()
    {
        $messages = [];
        foreach ($this->getTestCaseReports() as $testCaseReport) {
            $messages += $testCaseReport->getMessages();
        }
        return $messages;
    }

    /**
     * 获取成功的测试套件报告
     * @return array
     */
    function getSuccessTestSuiteReports()
    {
        return array_filter($this->getTestSuiteReports(), function(TestSuiteReport $testSuiteReport){
            return $testSuiteReport->getTestResult();
        });
    }

    /**
     * 获取失败的测试套件报告
     * @return array
     */
    function getFailedTestSuiteReports()
    {
        return array_filter($this->getTestSuiteReports(), function(TestSuiteReport $testSuiteReport){
            return !$testSuiteReport->getTestResult();
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
            'testSuiteNum' => count($this->getTestSuiteReports()),
            'testSuiteSuccessNum' => count($this->getSuccessTestSuiteReports()),
            'testSuiteFailedNum' => count($this->getFailedTestSuiteReports())
        ];
    }
}