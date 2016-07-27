<?php
namespace Slince\Example\TestCase;

use Slince\Mechanic\TestCase\WebUiTestCase;
use Webmozart\Assert\Assert;

class PageTitleTest extends WebUiTestCase
{
    function testTitle()
    {
        $this->getWebDriver()->get('http://www.w3.org/');
        $title = $this->getWebDriver()->getTitle();
        Assert::contains($title, 'W3C');
    }
}