<?php
namespace Slince\Example;

use slince\Mechanic\Mechanic;

class AppMechanic extends Mechanic
{
    function initialize()
    {
        //设置自动加载目录
        $this->getClassLoader()->setPsr4(__NAMESPACE__, __DIR__);
        //把配置目录下的文件全部加载
        $this->getConfigs()->load($this->getConfigPath());
    }
}