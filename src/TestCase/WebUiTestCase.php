<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\TestCase;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Slince\Mechanic\Mechanic;

class WebUiTestCase extends TestCase
{
    /**
     * @var RemoteWebDriver
     */
    protected $webDriver;

    function __construct(Mechanic $mechanic = null)
    {
        parent::__construct($mechanic);
        $host = 'http://localhost:4444/wd/hub';
        $this->webDriver = RemoteWebDriver::create($host, DesiredCapabilities::firefox());
    }

    /**
     * @return RemoteWebDriver
     */
    public function getWebDriver()
    {
        return $this->webDriver;
    }
}