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

class Migrate extends \Deimos\CLI\ClassProcessor
{

    public function actionHelp()
    {

    }

}

class Verbose extends \Deimos\CLI\ClassProcessor
{

    public function actionHelp()
    {

    }

}

$cliProcessor = new \Deimos\CLI\Processor($argv);

// register first processor
$cli = $cliProcessor->register(Verbose::class);

$cli->variable('verbose')
    ->optional()
    ->alias('v');

// register second processor
$cli = $cliProcessor->register(Migrate::class);

$cli->variable('migrate')
    ->optional()
    ->alias('m');

foreach ($cliProcessor as $cli)
{

    var_dump($cli);

    var_dump($cli->build());

    var_dump($cli->variables());
    var_dump($cli->aliases());

}