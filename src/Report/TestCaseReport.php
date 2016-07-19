<?php
/**
 * slince runner library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\Report;

use Slince\Mechanic\TestCase\TestCase;

class TestCaseReport
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
}