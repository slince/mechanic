<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace slince\Mechanic\TestCase;

use slince\Mechanic\Mechanic;

class TestCase
{
    /**
     * @var Mechanic
     */
    protected $mechanic;

    function __construct(Mechanic $mechanic)
    {
        $this->mechanic = $mechanic;
    }

    function getGlobalParameters()
    {
        return $this->mechanic->getParameters();
    }

    /**
     * @return Mechanic
     */
    public function getMechanic()
    {
        return $this->mechanic;
    }
}