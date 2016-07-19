<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace slince\Mechanic;

use Slince\Di\Container;

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
     * 测试用例
     * @var array
     */
    protected $testCases = [];

    function __construct()
    {
        $this->container = new Container();
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
}