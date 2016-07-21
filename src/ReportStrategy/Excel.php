<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\ReportStrategy;

class Excel extends ReportStrategy
{
    protected 
    protected $excel;

    function __construct()
    {
        $this->excel = new \PHPExcel();
    }

    
}