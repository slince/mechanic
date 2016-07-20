<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\Command;

use Slince\Example\AppMechanic;
use slince\Mechanic\Mechanic;
use Slince\Mechanic\EventStore;
use Slince\Mechanic\Exception\InvalidArgumentException;
use slince\Mechanic\TestSuite;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
     * @var Mechanic
     */
    protected $mechanic;

    function configure()
    {
        $this->setName(static::NAME);
        $this->addArgument('src', InputArgument::OPTIONAL, 'Test project location', getcwd());
        $this->addOption('suite', 's', InputOption::VALUE_IS_ARRAY & InputOption::VALUE_OPTIONAL, 'Test suite you want execute, default all');
    }

    function execute(InputInterface $input, OutputInterface $output)
    {
        $this->src = $input->getArgument('src');
        $testSuiteNames = $input->getOption('suite');
        $bootFile = "{$this->src}/src/AppMechanic.php";
        if (!file_exists($bootFile)) {
            throw new InvalidArgumentException(sprintf("You should create \"AppMechanic.php\" at [%s]", $this->src . '/src'));
        }
        include $bootFile;
        $mechanic = new AppMechanic();
        $testSuites = $this->createTestSuites($mechanic);
        $testSuites = empty($testSuites) ? [$this->createDefaultTestSuite($mechanic)] : $testSuites;
        $mechanic->setTestSuites($testSuites);
        $this->bindEventsForUi($mechanic, $output);
        $mechanic->run($testSuiteNames);
    }

    /**
     * 绑定事件
     * @param Mechanic $mechanic
     * @param OutputInterface $output
     */
    protected function bindEventsForUi(Mechanic $mechanic, OutputInterface $output)
    {
        $dispatcher = $mechanic->getDispatcher();
        $dispatcher->bind(EventStore::MECHANIC_RUN, function(Event $event) use ($output){
            $testSuites = $event->getArgument('testSuites');
            $total = count($testSuites);
            $output->writeln("Mechanic will be performed {$total} test suites, Please wait a moment");
            $output->write(PHP_EOL);
        });
        //执行单元套件
        $dispatcher->bind(EventStore::TEST_SUITE_EXECUTE, function(Event $event) use($output){
            $testSuite = $event->getArgument('testSuite');
            $output->writeln("Processing test suite \"{$testSuite->getName()}\"");
        });
        //测试任务执行完毕
        $dispatcher->bind(EventStore::TEST_SUITE_EXECUTED, function(Event $event) use($output){
            $testSuite = $event->getArgument('testSuite');
        });
        $dispatcher->bind(EventStore::MECHANIC_FINISH, function() use ($output){
            $output->writeln(PHP_EOL);
            $output->writeln("Mechanic stop");
        });
    }

    /**
     * 创建默认的测试套件
     * @param Mechanic $mechanic
     * @return TestSuite
     */
    function createDefaultTestSuite(Mechanic $mechanic)
    {
        //找出所有的php文件
        $files = static::getFinder()->files()->name('*.php')->in("{$this->src}/TestCase");
        $testCases = [];
        foreach ($files as $file) {
            $testCaseClass = "{$mechanic->getNamespace()}\\TestCase\\" . $file->getBasename('.php');
            $testCases[] = new $testCaseClass();
        }
        return new TestSuite('default', $testCases);
    }

    /**
     * 创建测试套件
     * @param Mechanic $mechanic
     * @return array
     */
    protected function createTestSuites(Mechanic $mechanic = null)
    {
        //找出所有的php文件
        $files = static::getFinder()->files()->name('*.php')->in("{$this->src}/TestSuite");
        $testSuites = [];
        foreach ($files as $file) {
            $testSuiteClass = "{$mechanic->getNamespace()}\\TestSuite\\" . $file->getBasename('.php');
            $testSuites[] = new $testSuiteClass();
        }
        return $testSuites;
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