<?php

namespace AloneWebMan\Js\Command;

use AloneWebMan\Js\Facade;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class JsExec extends Command {
    protected static $defaultName        = 'alone:js';
    protected static $defaultDescription = 'down js file <info>[update]</info>';

    protected function configure(): void {
        $this->addArgument('update', InputArgument::OPTIONAL, 'update');
    }

    public function execute(InputInterface $input, OutputInterface $output): int {
        $update = $input->getArgument('update');
        $down = config('plugin.alone.js.app.down', []);
        Facade::downFile($down, !empty($update));
        Facade::updateRoute(config('plugin.alone.js.app.route', []), $down);
        return self::SUCCESS;
    }
}