<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

$cli = new \Deimos\CLI\CLI($argv);

$cli->variable('file')
    ->alias('f')
    ->required();

$cli->variable('verbosity')
    ->alias('v')
    ->defaultValue(true)
    ->boolType();

$cli->variable('version')
    ->alias('ver')
    ->boolType()
    ->help('Get version info');

$cli->run();

var_dump($cli->commands());