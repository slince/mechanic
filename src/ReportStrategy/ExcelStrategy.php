<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\ReportStrategy;

class ExcelStrategy extends ReportStrategy
{
    protected $excel;

    function __construct()
    {
        $this->excel = new \PHPExcel();
    }

    
}