<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\ReportStrategy;

use Symfony\Component\Console\Helper\Table;

class ScreenPretty extends ReportStrategy
{
    function execute()
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
        $output->writeln(__('Summary'));
        $table->render();
        //计算测试用例数据
        $table = new Table($this->getMechanic()->getCommand()->getOutput());
        $table->setHeaders([__('Name'), __('Test number'), __('Success Number'),
            __('Failed Number'), __('Success Rate'), __('Failed Rate')]);
        $rows = [];
        foreach ($analysis['testSuiteAnalysis'] as $testSuiteAnalysis) {
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
        $output->writeln(__('TestSuite'));
        $table->render();
        $output->writeln(__('More report information please used other strategy'));
    }
}