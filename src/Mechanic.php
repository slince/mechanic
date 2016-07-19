<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace slince\Mechanic;

use Slince\Config\Config;
use Slince\Di\Container;
use Slince\Cache\ArrayCache;
use Slince\Event\Dispatcher;
use Slince\Mechanic\Report\TestMethodReport;
use slince\Mechanic\TestCase\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Composer\Autoload\ClassLoader;

class Mechanic
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var ArrayCache
     */
    protected $parameters;

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Config
     */
    protected $configs;
    /**
     * 测试用例
     * @var array
     */
    protected $testCases = [];

    /**
     * class loader
     * @var ClassLoader
     */
    protected $classLoader;

    /**
     * 测试项目根目录
     * @var string
     */
    protected $rootPath;

    /**
     * 命令空间
     * @var string
     */
    protected $namespace;

    function __construct()
    {
        $this->container = new Container();
        $this->dispatcher = new Dispatcher();
        $this->parameters = new ArrayCache();
        $this->filesystem = new Filesystem();
        $this->configs = new Config();
        $this->classLoader = new ClassLoader();
        $this->initialize();
    }

    function initialize()
    {
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return array
     */
    public function getTestCases()
    {
        return $this->testCases;
    }

    /**
     * @return ArrayCache
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * 获取自动加载器
     * @return ClassLoader
     */
    public function getClassLoader()
    {
        return $this->classLoader;
    }

    /**
     * @return Config
     */
    public function getConfigs()
    {
        return $this->configs;
    }

    function run()
    {
        foreach ($this->testCases as $testCase) {
            $this->runTestCase($testCase);
        }
    }

    /**
     * 执行测试用例
     * @param TestCase $testCase
     */
    function runTestCase(TestCase $testCase)
    {
        $testMethods = $this->getTestCaseTestMethods($testCase);
        foreach ($testMethods as $testMethod) {
            try {
                //执行用例方法，如果方法没有明确返回false，则用例方法算执行成功
                $result = $testMethod->invoke();
                $result = ($result !== false);
                $message = 'Fail';
            } catch (\Exception $e) {
                $result = false;
                $message = $e->getMessage();
            }
            //记录用例方法的测试报告到用例报告
            $testCase->getTestCaseReport()->addTestMethodReport(TestMethodReport::create($testMethod, $result, [$message]));
        }
    }

    /**
     * @param TestCase $testCase
     * @return \ReflectionMethod[]
     */
    protected function getTestCaseTestMethods(TestCase $testCase)
    {
        $reflection = new \ReflectionObject($testCase);
        $publicMethods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
        $testMethods = [];
        foreach ($publicMethods as $method) {
            if (strcasecmp(substr($method->getName(), 0, 4), 'test') == 0) {
                $testMethods[] = $method;
            }
        }
        return $testMethods;
    }

    /**
     * 获取根目录
     * @return string
     */
    function getRootPath()
    {
        if (is_null($this->rootPath)) {
            $reflection = new \ReflectionObject($this);
            $this->rootPath = dirname($reflection->getFileName());
        }
        return $this->rootPath;
    }

    /**
     * 获取配置文件目录
     * @return string
     */
    function getConfigPath()
    {
        return $this->getRootPath() . '/config';
    }

    /**
     * 获取当前application的命名空间
     * @return string
     */
    function getNamespace()
    {
        if (is_null($this->namespace)) {
            $this->namespace = (new \ReflectionClass($this))->getNamespaceName();
        }
        return $this->namespace;
    }
}