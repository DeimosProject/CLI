<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

$cli = new \Deimos\CLI\CLI($argv);

$cli->variable('file')
    ->alias('f')
    ->required();

$cli->variable('verbosity')
    ->alias('v')
    ->boolType();

$cli->variable('version')
    ->alias('ver')
    ->boolType();

$cli->run();

var_dump($cli->commands());
var_dump($cli->storage());