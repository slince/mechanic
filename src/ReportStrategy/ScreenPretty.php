<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\ReportStrategy;

use Slince\Mechanic\Exception\InvalidArgumentException;
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
        $this->convertToTable($this->getSummaryTable())->render();
        $output->write(PHP_EOL);
        $output->writeln(__('TestSuite'));
        $this->convertToTable($this->getTestSuiteSummaryTable())->render();

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
            foreach ($testSuite->getTestCases() as $testCase) {
                $this->convertToTable($this->getTestCaseTable($testCase))->render();
            }
            $continue = $questionHelper->ask($input, $output, new ConfirmationQuestion(__("Continue? ")));
        } while($continue);
        $output->write(PHP_EOL);
        $output->writeln(__('Tips: More report information please used other strategy.'));
    }

    /**
     * 转换report table到 table
     * @param ReportTable $reportTable
     * @return Table
     */
    protected function convertToTable(ReportTable $reportTable)
    {
        $output = $this->getMechanic()->getCommand()->getOutput();
        $table = new Table($output);
        $table->setHeaders($reportTable->getHeaders());
        $table->setRows($reportTable->getRows());
        return $table;
    }
}