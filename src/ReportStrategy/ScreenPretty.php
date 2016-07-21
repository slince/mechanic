<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\ReportStrategy;

use Slince\Mechanic\Exception\InvalidArgumentException;
use Slince\Mechanic\TestCase\TestCase;
use Slince\Mechanic\TestSuite;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class ScreenPretty extends ReportStrategy
{
    /**
     * 执行策略
     */
    function execute()
    {
        $output = $this->getMechanic()->getCommand()->getOutput();
        $output->write(PHP_EOL);
        $output->writeln(__('Summary'));
        $this->makeSummaryTable()->render();
        $output->write(PHP_EOL);
        $output->writeln(__('TestSuite'));
        $this->makeTestSuiteSummaryTable()->render();

        $question = new Question(__("Please input test suite name for see more information: "));
        $question->setValidator(function($answer){
            $answer = trim($answer);
            if (empty($answer)) {
                throw new InvalidArgumentException(__("You should input a valid test suite name"));
            }
            if (($testSuite = $this->getMechanic()->getTestSuite(trim($answer))) == null) {
                throw new InvalidArgumentException(__("Can not find test suite [{0}]", $answer));
            }
            return $testSuite;
        });

        $input = $this->getMechanic()->getCommand()->getInput();
        $questionHelper = $this->getMechanic()->getCommand()->getHelper('question');
        do {
            $output->write(PHP_EOL);
            $testSuite = $questionHelper->ask($input, $output, $question);
            $tables = $this->makeTestSuiteTables($testSuite);
            foreach ($tables as $table) {
                $table->render();
            }
            $continue = $questionHelper->ask($input, $output, new ConfirmationQuestion(__("Continue? ")));
        } while($continue);
        $output->write(PHP_EOL);
        $output->writeln(__('Tips: More report information please used other strategy.'));
    }

    /**
     * 创建概要
     * @return Table
     */
    protected function makeSummaryTable()
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
        return $table;
    }

    /**
     * 创建测试套件概要
     * @return Table
     */
    protected function makeTestSuiteSummaryTable()
    {
        $output = $this->getMechanic()->getCommand()->getOutput();
        //计算测试用例数据
        $table = new Table($output);
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
     * 生成测试套件的多表格
     * @param TestSuite $testSuite
     * @return array
     */
    protected function makeTestSuiteTables(TestSuite $testSuite)
    {
        $tables = [];
        foreach ($testSuite->getTestCases() as $testCase) {
            $tables[] = $this->makeTestCaseTable($testCase);
        }
        return $tables;
    }

    /**
     * 生成测试用例table
     * @param TestCase $testCase
     * @return Table
     */
    protected function makeTestCaseTable(TestCase $testCase)
    {
        $output = $this->getMechanic()->getCommand()->getOutput();
        //计算测试用例数据
        $table = new Table($output);
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