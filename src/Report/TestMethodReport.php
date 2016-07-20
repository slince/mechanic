<?php
/**
 * slince runner library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\Report;

class TestMethodReport implements ReportInterface
{
    /**
     * @var \ReflectionMethod
     */
    protected $method;

    /**
     * @var TestCaseReport
     */
    protected $testCaseReport;

    /**
     * @var boolean
     */
    protected $testResult;

    /**
     * @var array
     */
    protected $messages = [];

    function __construct($method, $testResult, array $messages = [])
    {
        $this->method = $method;
        $this->testResult = $testResult;
        $this->messages = $messages;
    }


    /**
     * @return \ReflectionMethod
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return TestCaseReport
     */
    public function getTestCaseReport()
    {
        return $this->testCaseReport;
    }

    /**
     * @param \ReflectionMethod $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @param TestCaseReport $testCaseReport
     */
    public function setTestCaseReport($testCaseReport)
    {
        $this->testCaseReport = $testCaseReport;
    }

    /**
     * @param boolean $testResult
     */
    public function setResult($testResult)
    {
        $this->testResult = $testResult;
    }

    /**
     * @return boolean
     */
    public function getTestResult()
    {
        return $this->testResult;
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
     * 添加一个message
     * @param $message
     */
    function addMessage($message)
    {
        $this->messages[] = $message;
    }

    /**
     * 分析报告
     * @return array
     */
    function analyze()
    {
        return [
            'result' => $this->getTestResult(),
            'messages' => $this->getMessages()
        ];
    }

    /**
     * 生成测试报告对象
     * @param $method
     * @param $testResult
     * @param array $messages
     * @return static
     */
    static function create($method, $testResult, array $messages = [])
    {
        return new static($method, $testResult, $messages);
    }
}