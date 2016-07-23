<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\ReportStrategy;

use Slince\Mechanic\Mechanic;
use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;

class EmailNotification extends ReportStrategy
{

    const CASE_ALL = 'all';

    const CASE_FAILED = 'failed';

    const CASE_SUCCESS = 'success';

    /**
     * 需要通知的邮箱
     * @var array
     */
    protected $recipients = [];

    /**
     * 发件邮箱
     * @var array
     */
    protected $senders = [];

    /**
     * @var array
     */
    protected $configs;

    /**
     * @var array
     */
    protected $notifyCases;

    function __construct(Mechanic $mechanic, array $recipients, array $senders, array $configs, $notifyCases = [])
    {
        parent::__construct($mechanic);
        $this->recipients = $recipients;
        $this->senders = $senders;
        $this->configs = $configs;
        $this->notifyCases = $notifyCases;
    }

    /**
     * 执行策略，发送邮件
     * @return bool|int
     */
    function execute()
    {
        if (!in_array(static::CASE_ALL, $this->notifyCases)) {
            $result = $this->getReport()->getTestResult();
            if ((!$result && !in_array(static::CASE_FAILED, $this->notifyCases)) || (
                    $result && !in_array(static::CASE_SUCCESS, $this->notifyCases)
                )) {
                return false;
            }
        }
        $mailer = Swift_Mailer::newInstance($this->createTransport($this->configs));
        return $mailer->send($this->makeMessage());
    }

    /**
     * @return \Swift_Mime_MimePart
     */
    protected function makeMessage()
    {
        return Swift_Message::newInstance()
            // Give the message a subject
            ->setSubject(__('Mechanic Test Report'))
            // Set the From address with an associative array
            ->setFrom($this->senders)
            // Set the To addresses with an associative array
            ->setTo($this->recipients)
            // Give it a body
            ->setBody($this->buildEmailBodyHtml(), 'text/html');
    }

    /**
     * @param array $config
     * @return Swift_SmtpTransport
     */
    protected function createTransport(array $config)
    {
        return Swift_SmtpTransport::newInstance($config['host'], $config['port'])
            ->setUsername($config['username'])
            ->setPassword($config['password']);
    }
    /**
     * 构建邮件message
     * @return string
     */
    protected function buildEmailBodyHtml()
    {
        $htmls = [];
        $htmls[] = "<h2>Summary</h2>";
        $htmls[] = $this->convertToHtml($this->getSummaryTable());
        $htmls[] = "<h2>Test suite Summary</h2>";
        $htmls[] = $this->convertToHtml($this->getTestSuiteSummaryTable());
        foreach ($this->getMechanic()->getExecuteTestSuites() as $testSuite) {
            $htmls[] = "<h2>{$testSuite->getName()}</h2>";
            foreach ($testSuite->getTestCases() as $testCase) {
                $htmls[] = $this->convertToHtml($this->getTestCaseTable($testCase));
            }
        }
        $date = date('Y-m-d H:i:s');
        $htmls[] = "<small>Mechanic {$date}</small>";
        return implode("\r\n", $htmls);
    }

    /**
     * 转换成html结构
     * @param ReportTable $reportTable
     * @return string
     */
    protected function convertToHtml(ReportTable $reportTable)
    {
        $html = '<table border="1" cellpadding="10" cellspacing="10" class="table" style="border:1px solid #ccc;border-collapse:collapse"><tr>';
        foreach ($reportTable->getHeaders() as $header) {
            $html .= "<th>{$header}</th>";
        }
        $html .= "</tr>";
        foreach ($reportTable->getRows() as $row) {
            $html .= "<tr>";
            foreach ($row as $cell) {
                $html .= "<td>{$cell}</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</table>";
        return $html;
    }
}