<?php
/**
 * slince runner library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\Report;

class TestMethodReport
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
    protected $result;

    /**
     * @var array
     */
    protected $messages = [];

    function __construct($method, $result, array $messages = [])
    {
        $this->method = $method;
        $this->result = $result;
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
     * @param boolean $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return boolean
     */
    public function getResult()
    {
        return $this->result;
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
     * 生成测试报告对象
     * @param $method
     * @param $result
     * @param array $messages
     * @return static
     */
    static function create($method, $result, array $messages = [])
    {
        return new static($method, $result, $messages);
    }
}