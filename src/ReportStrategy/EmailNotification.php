<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\ReportStrategy;

use Slince\Mechanic\Mechanic;

class EmailNotification extends ReportStrategy
{

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

    function __construct(Mechanic $mechanic, array $recipients, array $senders, $notifyCases = [])
    {

    }

    function execute()
    {
        $result = $this->getReport()->getTestResult();
        if () {

        }
    }

    function makeMessage()
    {
        return Swift_Message::newInstance()
            // Give the message a subject
            ->setSubject(__('Mechanic Test Report'))
            // Set the From address with an associative array
            ->setFrom($this->senders)
            // Set the To addresses with an associative array
            ->setTo($this->recipients)
            // Give it a body
            ->setBody('Here is the message itself')
            // And optionally an alternative body
            ->addPart('<q>Here is the message itself</q>', 'text/html')
            // Optionally add any attachments
            ->attach(Swift_Attachment::fromPath('my-document.pdf'));
    }

    /**
     * 创建概要
     * @return Table
     */
    protected function makeSummaryTable()
    {
        $output = $this->getMechanic()->getCommand()->getOutput();
        $analysis = $this->getReport()->analyze();
        $table = new Table($output);
        $table->setHeaders([__('Execute Result'), __('Test Suite Number'),
            __('Success Suite Number'), __('Failed Suite Number')]);
        $table->setRows([
            [
                $analysis['result'] ? __('Success') : __('Failed'),
                $analysis['testSuiteNum'],
                $analysis['testSuiteSuccessNum'],
                $analysis['testSuiteFailedNum'],
            ]
        ]);
        return $table;
    }
}