<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\ReportStrategy;

use Slince\Mechanic\Mechanic;

class Excel extends ReportStrategy
{
    /**
     * office excel 2007
     * @var string
     */
    const EXCEL2007 = 'Excel2007';

    /**
     * office excel
     * @var string
     */
    const EXCEL5 = 'Excel5';

    /**
     * @var string
     */
    protected $type;

    protected $excel;

    function __construct(Mechanic $mechanic, $type = self::EXCEL2007)
    {
        $this->type = $type;
        $this->excel = new \PHPExcel();
        parent::__construct($mechanic);
    }

    /**
     * @return \PHPExcel
     */
    public function getExcel()
    {
        return $this->excel;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    function execute()
    {

    }

    /**
     * 创建summary sheet
     * @return \PHPExcel_Worksheet
     */
    function makeSummarySheet()
    {
        $sheet = new \PHPExcel_Worksheet($this->getExcel(), __('Summary'));
        $analysis = $this->getReport()->analyze();
        $sheet->setCellValue('A1', __('Execute Result'))
            ->setCellValue('B1',  __('Test Suite Number'))
            ->setCellValue('C1',  __('Success Suite Number'))
            ->setCellValue('D1', __('Failed Suite Number'));
        $sheet->setCellValue('A2', $analysis['result'] ? __('Success') : __('Failed'))
            ->setCellValue('B2',  $analysis['testSuiteNum'])
            ->setCellValue('C2',  $analysis['testSuiteSuccessNum'])
            ->setCellValue('D2', $analysis['testSuiteFailedNum']);
        return $sheet;
    }

    /**
     * 创建测试套件概要
     * @return \PHPExcel_Worksheet
     */
    protected function makeTestSuiteSummaryTable()
    {
        $sheet = new \PHPExcel_Worksheet($this->getExcel(), __('TestSuite'));
        //计算测试用例数据
        $sheet->setCellValue('A1',__('Name'))
            ->setCellValue('B1',  __('Test number'))
            ->setCellValue('C1',  __('Success Number'))
            ->setCellValue('D1', __('Failed Number'))
            ->setCellValue('E1', __('Success Rate'))
            ->setCellValue('F1', __('Failed Rate'));
        foreach (array_values($this->getMechanic()->getTestSuites()) as $key => $testSuite) {
            $testSuiteAnalysis = $testSuite->getTestSuiteReport()->analyze();
            $key += 2;
            $sheet->setCellValue("A{$key}", $testSuiteAnalysis['name'])
                ->setCellValue("B{$key}", $testSuiteAnalysis['testCaseNum'])
                ->setCellValue("C{$key}", $testSuiteAnalysis['testCaseSuccessNum'])
                ->setCellValue("D{$key}", $testSuiteAnalysis['testCaseFailedNum'])
                ->setCellValue("E{$key}", (number_format($testSuiteAnalysis['testCaseSuccessNum'] / $testSuiteAnalysis['testCaseNum'], 4) * 100) . '%')
                ->setCellValue("F{$key}", (number_format($testSuiteAnalysis['testCaseFailedNum'] / $testSuiteAnalysis['testCaseNum'], 4) * 100) . '%');
        }
        return $sheet;
    }
}