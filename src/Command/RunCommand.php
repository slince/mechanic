<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\Command;

use Slince\Example\AppRunner;
use Slince\Runner\EventStore;
use Slince\Runner\ExaminationChain;
use Slince\Runner\Exception\InvalidArgumentException;
use Slince\Runner\Runner;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Finder\Finder;
use Slince\Event\Event;

class RunCommand extends Command
{
    const NAME = 'run';

    /**
     * @var Finder
     */
    static $finder;

    /**
     * 资源位置
     * @var string
     */
    protected $src;

    /**
     * @var ExaminationChain
     */
    protected $examinations;

    /**
     * @var Runner
     */
    protected $runner;

    function configure()
    {
        $this->setName(static::NAME);
        $this->addArgument('src', InputArgument::OPTIONAL, 'Test project location', getcwd() . '/src');
    }

    function execute(InputInterface $input, OutputInterface $output)
    {
        $this->src = $input->getArgument('src');
        $bootFile = "{$this->src}/AppRunner.php";
        if (!file_exists($bootFile)) {
            throw new InvalidArgumentException(sprintf("You should create \"AppRunner.php\" at [%s]", $this->src));
        }
        include $bootFile;
        $this->examinations = new ExaminationChain();
        $this->runner = new AppRunner($this->examinations);
        $this->examinations->addAll($this->createExaminations($this->runner));
        $this->createTestCases($this->runner);
        $this->bindEventsForUi($this->runner, $output);
        $this->runner->run();
    }

    /**
     * 绑定事件
     * @param Runner $runner
     * @param OutputInterface $output
     */
    protected function bindEventsForUi(Runner $runner, OutputInterface $output)
    {
        $chain = $runner->getExaminationChain();
        $dispatcher = $runner->getDispatcher();
        $dispatcher->bind(EventStore::RUNNER_RUN, function() use ($output, $chain){
            $examinationNum = count($chain);
            $output->writeln("Runner will be performed {$examinationNum} tasks, Please wait a moment");
            $output->write(PHP_EOL);
        });
        //执行新的测试任务
        $dispatcher->bind(EventStore::EXAMINATION_EXECUTE, function(Event $event) use($output){
            $examination = $event->getArgument('examination');
            $output->writeln("Processing examination \"{$examination->getName()}\"");
        });
        //测试任务执行完毕
        $dispatcher->bind(EventStore::EXAMINATION_EXECUTED, function(Event $event) use($output){
            $examination = $event->getArgument('examination');
        });
        $dispatcher->bind(EventStore::RUNNER_FINISH, function() use ($output){
            $output->writeln(PHP_EOL);
            $output->writeln("Runner stop,Generating test report");
        });
        $dispatcher->bind(EventStore::RUNNER_FINISH, function() use ($runner){
            $this->makeReport($runner);
        });
    }

    /**
     * 创建测试实例
     * @param Runner $runner
     * @return array
     */
    protected function createExaminations(Runner $runner)
    {
        //找出所有的php文件
        $files = static::getFinder()->files()->name('*.php')->in("{$this->src}/Examination");
        $examinations = [];
        foreach ($files as $file) {
            $examinationClass = "{$runner->getNamespace()}\\Examination\\" . $file->getBasename('.php');
            $examinations[] = new $examinationClass();
        }
        return $examinations;
    }

    /**
     * 创建测试用例
     * @param Runner $runner
     * @return array
     */
    protected function createTestCases(Runner $runner)
    {
        //找出所有的php文件
        $files = static::getFinder()->files()->name('*.php')->in("{$this->src}/TestCase");
        $testCases = [];
        foreach ($files as $file) {
            $testCaseClass = "{$runner->getNamespace()}\\TestCase\\" . $file->getBasename('.php');
            $testCases[] = new $testCaseClass($runner);
        }
        return $testCases;
    }

    /**
     * @return Finder
     */
    public static function getFinder()
    {
        self::$finder = new Finder();
        return self::$finder;
    }
}