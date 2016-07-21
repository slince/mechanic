<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\ReportStrategy;

use Slince\Mechanic\Mechanic;
use PHPExcel;

class Excel extends ReportStrategy
{
    /**
     * office excel 2007，默认是该类型
     * @var string
     */
    const TYPE_EXCEL2007 = 'Excel2007';

    /**
     * office excel
     * @var string
     */
    const TYPE_EXCEL5 = 'Excel5';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var PHPExcel
     */
    protected $excel;

    /**
     * 文件扩展名
     * @var array
     */
    protected static $extensions = [
        self::TYPE_EXCEL5 => '.xls',
        self::TYPE_EXCEL2007 => '.xlsx'
    ];

    function __construct(Mechanic $mechanic, $type = self::TYPE_EXCEL2007)
    {
        $this->type = $type;
        $this->excel = new PHPExcel();
        parent::__construct($mechanic);
    }

    /**
     * @return PHPExcel
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
        $this->initializeExcel($this->excel);
        $this->excel->addSheet($this->makeSummarySheet(), 0);
        $this->excel->addSheet($this->makeSummarySheet(), 1);
        $writer = PHPExcel_IOFactory::createWriter($this->excel, $this->type);
        $writer->save($this->getFileName());
    }

    /**
     * 初始化excel
     * @param PHPExcel $excel
     */
    protected function initializeExcel(PHPExcel $excel)
    {
        $excel->getProperties()->setCreator('Mechanic')
            ->setLastModifiedBy('Mechanic')
            ->setTitle('Mechanic Test Repoprt')
            ->setSubject('Mechanic Test Repoprt')
            ->setDescription('Mechanic Test Repoprt')
            ->setKeywords('Mechanic Test Report')
            ->setCategory("Report");
    }

    /**
     * 获取文件名
     * @return string
     */
    protected function getFileName()
    {
        return $this->getMechanic()->getReportPath() . DIRECTORY_SEPARATOR . time() . rand(10, 99) . $this->getExtension();
    }

    /**
     * 获取文件扩展名
     * @return string
     */
    protected function getExtension()
    {
        return isset(static::$extensions[$this->type]) ? static::$extensions[$this->type] : '';
    }

    /**
     * 创建summary sheet
     * @return PHPExcel_Worksheet
     */
    protected function makeSummarySheet()
    {
        $sheet = new PHPExcel_Worksheet($this->getExcel(), __('Summary'));
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
     * @return PHPExcel_Worksheet
     */
    protected function makeTestSuiteSummarySheet()
    {
        $sheet = new PHPExcel_Worksheet($this->getExcel(), __('TestSuite'));
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

    /**
     * 生成测试用例sheet
     * @param TestCase $testCase
     * @return PHPExcel_Worksheet
     */
    protected function makeTestCaseTable(TestCase $testCase)
    {
        $sheet = new PHPExcel_Worksheet($this->getExcel(), __('TestSuite'));
        //计算测试用例数据
        $sheet->setCellValue('A1', __('Test Method'))
            ->setCellValue('B1', __('Test Result'))
            ->setCellValue('C1', __('Messages'));
        foreach (array_values($testCase->getTestCaseReport()->getTestMethodReports()) as $key => $testMethodReport) {
            $key += 2;
            $sheet->setCellValue("A{$key}", $testMethodReport->getMethod()->getName())
                ->setCellValue("B{$key}", $testMethodReport->getTestResult() ? __('Success') : __('Failed'))
                ->setCellValue("C{$key}", implode(PHP_EOL, $testMethodReport->getMessages()) ?: 'None');
        }
        return $sheet;
    }


    protected function convertToSheet(ReportTable $reportTable)
    {
        $sheet = new PHPExcel_Worksheet($this->getExcel(), __('TestSuite'));
        foreach ($reportTable->getHeaders() as $key => $header) {
            $key += 1;

        }
        //计算测试用例数据
        $sheet->setCellValue('A1', __('Test Method'))
            ->setCellValue('B1', __('Test Result'))
            ->setCellValue('C1', __('Messages'));
        foreach (array_values($testCase->getTestCaseReport()->getTestMethodReports()) as $key => $testMethodReport) {
            $key += 2;
            $sheet->setCellValue("A{$key}", $testMethodReport->getMethod()->getName())
                ->setCellValue("B{$key}", $testMethodReport->getTestResult() ? __('Success') : __('Failed'))
                ->setCellValue("C{$key}", implode(PHP_EOL, $testMethodReport->getMessages()) ?: 'None');
        }
        return $sheet;
    }
}