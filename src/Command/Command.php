<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\Command;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Slince\Runner\ExaminationChain;
use Slince\Runner\Runner;
use Slince\Runner\Examination;
use PHPExcel;

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

    function configure()
    {
        $this->addOption(self::CONFIG_OPTION, null, InputOption::VALUE_OPTIONAL, '配置文件',
            getcwd() . DIRECTORY_SEPARATOR . self::CONFIG_FILE
        );
    }

    function validateConfigFile($configFile)
    {

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

    /**
     * 提取报告数据
     * @param ExaminationChain $examinationChain
     * @return array
     */
    protected function extractDataFromChain(ExaminationChain $examinationChain)
    {
        $datas = [];
        foreach ($examinationChain as $examination) {
            $data = [
                'name' => $examination->getName(),
                'url' => $examination->getApi()->getUrl(),
                'method' => $examination->getApi()->getMethod(),
                'status' => $this->getStatusText($examination->getStatus()),
                'remark' => implode("\r\n", $examination->getReport()->getMessages()),
            ];
            $datas[] = $data;
        }
        return $datas;
    }

    /**
     * 将所有断言中的message迭代出来
     * @param array $assertions
     * @return string
     */
    protected function reduceAssertionsMessage(array $assertions)
    {
        $messages = [];
        foreach ($assertions as $assertion) {
            $messages[] = $assertion->getMessage();
        }
        return implode(';', array_filter($messages));
    }
    /**
     * 将断言结果迭代成可存储的字符串
     * @param array $assertions
     * @return string
     */
    protected function reduceAssertionsResults(array $assertions)
    {
        $results = [];
        foreach ($assertions as $assertion) {
            $result = implode(':', [
                $assertion->getMethod(),
                print_r($assertion->getParameters(), true),
                $assertion->getExecutedResult() ? 'true' : 'false'
            ]);
            $results[] = $result;
        }
        return implode(PHP_EOL, $results);
    }

    /**
     * 获取状态描述
     * @param $status
     * @return string
     */
    protected function getStatusText($status)
    {
        static $texts = [
            Examination::STATUS_SUCCESS => '成功',
            Examination::STATUS_FAILED => '失败',
            Examination::STATUS_INTERRUPT => '中断',
            Examination::STATUS_WAITING => '等待'
        ];
        return isset($texts[$status]) ? $texts[$status] : '未知';
    }
}