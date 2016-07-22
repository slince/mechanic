<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\ReportStrategy;

use Slince\Mechanic\Exception\InvalidArgumentException;
use Slince\Mechanic\Mechanic;
use PHPExcel_Settings;

class Pdf extends Excel
{
    const TYPE_PDF = 'PDF';

    function __construct(Mechanic $mechanic)
    {
        throw new InvalidArgumentException(__("Strategy Pdf is unavailable"));
        parent::__construct($mechanic, static::TYPE_PDF);
        !PHPExcel_Settings::setPdfRenderer(
            PHPExcel_Settings::PDF_RENDERER_DOMPDF,
            ''
        );
        define('DOMPDF_ENABLE_AUTOLOAD', false);
    }

    /**
     * 获取文件扩展名
     * @return string
     */
    protected function getExtension()
    {
        return '.pdf';
    }
}