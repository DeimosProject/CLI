#!/usr/local/bin/php
<?php



$cli = new Deimos\CLI\CLI($argv);

$cli->variable('migrate')
    ->alias('m');

var_dump($cli->build());