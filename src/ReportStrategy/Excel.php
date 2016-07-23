<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\ReportStrategy;

use Slince\Mechanic\Mechanic;
use Slince\Mechanic\TestCase\TestCase;
use PHPExcel;
use PHPExcel_Worksheet;
use PHPExcel_IOFactory;
use Slince\Mechanic\TestSuite;

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

    /**
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    function execute()
    {
        $output = $this->getMechanic()->getCommand()->getOutput();
        $this->excel->addSheet($this->makeSummarySheet(), 0);
        foreach ($this->getMechanic()->getExecuteTestSuites() as $key => $testSuite) {
            $key ++;
            $this->excel->addSheet($this->makeTestSuiteSheet($testSuite), $key);
        }
        $writer = PHPExcel_IOFactory::createWriter($this->excel, $this->type);
        try {
            $this->initializeExcel($this->excel);
            $writer->save($this->getFileName());
        } catch (\PHPExcel_Exception $e) {
            $output->writeln($e->getMessage());
        }
        $output->write(PHP_EOL);
        $output->writeln(__("Make Report OK."));
    }

    /**
     * 初始化excel
     * @param PHPExcel $excel
     */
    protected function initializeExcel(PHPExcel $excel)
    {
        $excel->getProperties()->setCreator(__('Mechanic'))
            ->setLastModifiedBy(__('Mechanic'))
            ->setTitle(__('Mechanic Test Report'))
            ->setSubject(__('Mechanic Test Report'))
            ->setDescription(__('Mechanic Test Report'))
            ->setKeywords(__('Mechanic Test Report'))
            ->setCategory(__("Report"));
        $excel->setActiveSheetIndex(0);
    }

    /**
     * 获取文件名
     * @return string
     */
    protected function  getFileName()
    {
        return $this->getMechanic()->getReportPath() . DIRECTORY_SEPARATOR . date('Ymd') . rand(10, 99) . $this->getExtension();
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
        $reportTable = $this->getSummaryTable();
        $rows = $reportTable->getRows();
        array_unshift($rows, [__('Summary')], $reportTable->getHeaders());
        //合并test suite summary
        $testSuiteSummaryTable = $this->getTestSuiteSummaryTable();
        $rows[] = [];
        $rows = array_merge($rows, [[__('TestSuite')]], [$testSuiteSummaryTable->getHeaders()], $testSuiteSummaryTable->getRows());
        $sheet->fromArray($rows);
        return $sheet;
    }

    /**
     * 生成单元测试的sheet
     * @param TestSuite $testSuite
     * @return PHPExcel_Worksheet
     * @throws \PHPExcel_Exception
     */
    protected function makeTestSuiteSheet(TestSuite $testSuite)
    {
        $sheet = new PHPExcel_Worksheet($this->getExcel(), substr($testSuite->getName(), 0 ,20));
        $rows = [];
        foreach ($testSuite->getTestCases() as $testCase) {
            $reportTable = $this->getTestCaseTable($testCase);
            $rows[] = [$testCase->getName()];
            $rows = array_merge($rows, [$reportTable->getHeaders()], $reportTable->getRows());
            $rows[] = [];
            if ($messages = $testCase->getTestCaseReport()->getMessages()) {
                $rows[] = [__("Messages"), implode("\r\n", $messages)];
            }
        }
        $sheet->fromArray($rows);
        return $sheet;
    }
}