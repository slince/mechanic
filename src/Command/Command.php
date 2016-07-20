<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\Command;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends BaseCommand
{
    /**
     * 默认的配置文件名
     * @var string
     */
    const CONFIG_FILE = 'runner.json';

    /**
     * 配置文件option名称
     * @var string
     */
    const CONFIG_OPTION = 'config';

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @return InputInterface
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    protected function makeReport(Runner $runner)
    {
        $excel = new PHPExcel();
        $sheet = $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'Url地址')
            ->setCellValue('C1', '请求方法')
            ->setCellValue('E1', '测试结果')
            ->setCellValue('F1', '备注');
        foreach ($this->extractDataFromChain($runner->getExaminationChain()) as $key => $data) {
            $key += 2;
            $sheet->setCellValue("A{$key}", $data['name'])
                ->setCellValue("B{$key}", $data['url'])
                ->setCellValue("C{$key}", $data['method'])
                ->setCellValue("E{$key}", $data['status'])
                ->setCellValue("F{$key}", $data['remark']);
        }

        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $filename = getcwd() . DIRECTORY_SEPARATOR . 'report.xlsx';
        if (file_exists($filename)) {
            $filename = str_replace('.xlsx', time() . '.xlsx', $filename);
        }
        $writer->save($filename);
    }

}