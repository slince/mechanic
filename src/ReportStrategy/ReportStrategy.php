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

    function getReport()
    {
        return $this->report;
    }
}