<?php
/**
 * slince mechanic library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Mechanic\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class NewProjectCommand extends Command
{
    /**
     * 项目模板位置
     * @var string
     */
    protected $examplePath;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->examplePath = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'examples';
        $this->filesystem = new Filesystem();
    }

    function configure()
    {
        $this->setName('new-project');
        $this->addArgument('name', InputArgument::REQUIRED, __("Project name"));
    }

    function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $projectPath = getcwd() . DIRECTORY_SEPARATOR . $name;
        $this->filesystem->mkdir($projectPath);
        $this->copy($this->examplePath, $projectPath);
        $output->writeln(__("Create Success!"));
    }

    /**
     * 复制项目
     * @param $src
     * @param $dst
     */
    protected function copy($src, $dst)
    {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir("{$src}/{$file}") ) {
                    $this->copy("{$src}/{$file}", "{$dst}/{$file}");
                } else {
                    $this->filesystem->copy("{$src}/{$file}", "{$dst}/{$file}");
                }
            }
        }
        closedir($dir);
    }
}