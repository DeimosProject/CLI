<!--#!/usr/local/bin/php-->
<?php

include_once __DIR__ . '/../vendor/autoload.php';

if (empty($argv))
{
    $argv = array(
        0 => 'cli.php',
        1 => 'hello',
        2 => 'world',
        3 => '-v',
        4 => 'true',
        5 => '-f',
        6 => 'yandex',
        7 => '--migrate',
        8 => 'deimos',
    );
}

$cli = new Deimos\CLI\CLI($argv);

$cli->variable('verbose')
    ->alias('v');

$cli->variable('migrate')
    ->optional()
    ->alias('m');

var_dump($cli);

var_dump($cli->build());

var_dump($cli->variables());
var_dump($cli->aliases());
