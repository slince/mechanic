<?php
namespace Slince\Example;

use Slince\Mechanic\Mechanic;
use Slince\Mechanic\ReportStrategy\EmailNotification;
use Slince\Mechanic\ReportStrategy\Excel;
use Slince\Mechanic\ReportStrategy\Pdf;
use Slince\Mechanic\ReportStrategy\ScreenPretty;

class AppMechanic extends Mechanic
{
    function initialize()
    {
        //设置自动加载目录
        $this->getClassLoader()->setPsr4(__NAMESPACE__ . '\\', __DIR__);
        //把配置目录下的文件全部加载
        $this->getConfigs()->load($this->getConfigPath());
        //添加报告策略
        $this->addReportStrategy(new ScreenPretty($this)); //打印在屏幕上
        $this->addReportStrategy(new Excel($this)); //生成excel报告
        $this->addReportStrategy(new Pdf($this)); //生成pdf报告
        $this->addReportStrategy(new EmailNotification($this, [
            'taosikai@yeah.net'
        ])); //邮件通知报告
    }
}