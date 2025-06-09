<?php
require_once __DIR__ . '/vendor/autoload.php';

use AloneWebMan\Js\Facade;

$app = include(__DIR__ . '/config/app.php');
$down = $app['down'] ?? [];
$save = ($app["save"] ?? '');
Facade::downFile($down, $save, true, true);
foreach (($app['route'] ?? []) as $rout => $arr) {
    Facade::updateRoute($rout, $arr, $down, $save);
}