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
        $app = config('plugin.alone.js.app', []);
        $config = $app['config'] ?? [];
        $name = $app['down'] ?? key($config);
        $down = $app['config'][$name] ?? [];
        $save = ($app["save"] ?? '') ?: __DIR__ . '/../../file/';
        $save = rtrim($save, '/') . "/" . $name . "/";
        Facade::downFile($down, $save, !empty($update), true);
        foreach (($app['route'] ?? []) as $rout => $arr) {
            Facade::updateRoute($rout, $arr, $down, $save, !empty($update));
        }
        @mkdir($save . 'types', 0777, true);
        copy_dir(__DIR__ . "/../../file/aloneAppTypes.js", $save . 'types/aloneApp.js');
        return self::SUCCESS;
    }
}