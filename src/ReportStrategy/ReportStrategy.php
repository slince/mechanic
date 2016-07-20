<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\ReportStrategy;

use Slince\Mechanic\Report\Report;

class ReportStrategy
{
    /**
     * @var Report
     */
    protected $report;

    /**
     * 获取测试报告
     * @return Report
     */
    function getReport()
    {
        return $this->report;
    }
}