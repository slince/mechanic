<?php
namespace Slince\Example\TestCase;

use Slince\Mechanic\TestCase\WebUiTestCase;

class PageTitleTest extends WebUiTestCase
{
    function testTitle()
    {
        $this->wd->get('http://www.w3.org/');
    }
}