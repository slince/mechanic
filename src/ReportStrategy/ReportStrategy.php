<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\ReportStrategy;

use Slince\Mechanic\Mechanic;
use Slince\Mechanic\TestCase\TestCase;
use Slince\Mechanic\TestSuite;
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

    /**
     * 获取概要
     * @return ReportTable
     */
    function getSummaryTable()
    {
        $analysis = $this->getReport()->analyze();
        $table = new ReportTable();
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

    /**
     * 创建测试套件概要
     * @return ReportTable
     */
    protected function makeTestSuiteSummaryTable()
    {
        //计算测试用例数据
        $table = new ReportTable();
        $table->setHeaders([__('Name'), __('Test number'), __('Success Number'),
            __('Failed Number'), __('Success Rate'), __('Failed Rate')]);
        $rows = [];
        foreach ($this->getMechanic()->getTestSuites() as $testSuite) {
            $testSuiteAnalysis = $testSuite->getTestSuiteReport()->analyze();
            $rows[] = [
                $testSuiteAnalysis['name'],
                $testSuiteAnalysis['testCaseNum'],
                $testSuiteAnalysis['testCaseSuccessNum'],
                $testSuiteAnalysis['testCaseFailedNum'],
                (number_format($testSuiteAnalysis['testCaseSuccessNum'] / $testSuiteAnalysis['testCaseNum'], 4) * 100) . '%',
                (number_format($testSuiteAnalysis['testCaseFailedNum'] / $testSuiteAnalysis['testCaseNum'], 4) * 100) . '%',
            ];
        }
        $table->setRows($rows);
        return $table;
    }

    /**
     * 生成测试用例table
     * @param TestCase $testCase
     * @return ReportTable
     */
    protected function makeTestCaseTable(TestCase $testCase)
    {
        //计算测试用例数据
        $table = new ReportTable();
        $table->setHeaders([__('Test Method'), __('Test Result'), __('Messages')]);
        $rows = [];
        foreach ($testCase->getTestCaseReport()->getTestMethodReports() as $testMethodReport) {
            $rows[] = [
                $testMethodReport->getMethod()->getName(),
                $testMethodReport->getTestResult() ? __('Success') : __('Failed'),
                implode(PHP_EOL, $testMethodReport->getMessages()) ?: 'None'
            ];
        }
        $table->setRows($rows);
        return $table;
    }
}