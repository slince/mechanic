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
        $this->addReportStrategy(new Excel($this)); //生成excel报告
        //this->addReportStrategy(new Pdf($this)); //生成pdf报告,pdf报告暂时不可用

        /**
        $cases = [EmailNotification::CASE_ALL];
        //邮件通知报告
        $this->addReportStrategy(new EmailNotification($this, $this->configs['recipients'], $this->configs['senders'], $this->configs['smtp'], $cases));
         */
        //控制台输出
        $this->addReportStrategy(new ScreenPretty($this)); //打印在屏幕上
    }
}