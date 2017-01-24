#!/usr/bin/php
<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

$cli = new \Deimos\CLI\CLI($argv);

$cli->variable('config')
    ->alias('c')
    ->required();

$cli->variable('path')
    ->alias('p')
    ->required();

$cli->run();

if (!class_exists('\Deimos\Config\Config'))
{
    die('composer upd --dev');
}

$builder = new Deimos\Builder\Builder();
$config  = new \Deimos\Config\Config(__DIR__ . '/configs', $builder);

var_dump($config->get($cli->config[0] . ':' . $cli->path[0]));