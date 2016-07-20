<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\ReportStrategy;

use Slince\Mechanic\Mechanic;
use Slince\Mechanic\Report\Report;

abstract class ReportStrategy
{
    /**
     * @var Report
     */
    protected $report;

    /**
     * @var Mechanic
     */
    protected $mechanic;

    function __construct(Mechanic $mechanic)
    {
        $this->mechanic = $mechanic;
        $this->report = $mechanic->getReport();
    }

    /**
     * 获取测试报告
     * @return Report
     */
    function getReport()
    {
        return $this->report;
    }

    /**
     * @return Mechanic
     */
    public function getMechanic()
    {
        return $this->mechanic;
    }

    /**
     * 执行报告策略
     * @return mixed
     */
    abstract function execute();
}