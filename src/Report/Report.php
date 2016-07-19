<?php
/**
 * slince runner library
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
     * 测试用例的测试报告
     * @var array
     */
    protected $testCaseReports = [];

    /**
     * 测试结果
     * @var boolean
     */
    protected $testResult;
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
     * @param $testResult
     */
    public function setTestResult($testResult)
    {
        $this->testResult = $testResult;
    }

    /**
     * @return bool
     */
    public function getTestResult()
    {
        foreach ($this->getTestCaseReports() as $testCaseReport) {
            if (!($testCaseReport->getPreRequestResult() && $testCaseReport->getAfterRequestResult())) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return array
     */
    public function getTestCaseReports()
    {
        return $this->testCaseReports;
    }

    /**
     * @param array $testCaseReports
     */
    public function setTestCaseReports($testCaseReports)
    {
        $this->testCaseReports = $testCaseReports;
    }

    /**
     * @param TestCaseReport $testCaseReport
     */
    public function addTestCaseReports($testCaseReport)
    {
        $this->testCaseReports[] = $testCaseReport;
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
}