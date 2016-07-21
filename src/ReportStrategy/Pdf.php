<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\ReportStrategy;

use Slince\Mechanic\Mechanic;

class Pdf extends Excel
{
    const TYPE_PDF = 'PDF';

    function __construct(Mechanic $mechanic)
    {
        parent::__construct($mechanic, static::TYPE_PDF);
    }

    /**
     * 获取文件扩展名
     * @return string
     */
    function getExtension()
    {
        return '.pdf';
    }
}