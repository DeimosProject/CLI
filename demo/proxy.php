#!/usr/bin/php
<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

$cli = new \Deimos\CLI\CLI($argv);

$cli->variable('verbosity')
    ->alias('v')
    ->defaultValue(true)
    ->boolType();

$cli->variable('test')
    ->alias('t')
    ->defaultValue(1)
    ->help('for debug');

class Project extends \Deimos\CLI\Controller
{

    /**
     * @return string
     *
     * @throws \Deimos\CLI\Exceptions\CLIRun
     */
    protected function commandDefault()
    {
        return json_encode($this->cli->asArray());
    }

    /**
     * @return string
     *
     * @throws \Deimos\CLI\Exceptions\CLIRun
     */
    protected function commandHello()
    {
        return serialize($this->cli->asArray());
    }

}

class Framework extends \Deimos\CLI\Processor
{

    /**
     * @return Project
     *
     * @throws \Deimos\CLI\Exceptions\Required
     * @throws \Deimos\CLI\Exceptions\UndefinedVariable
     */
    protected function buildProject()
    {
        return new Project($this->cli);
    }

}

class Runner extends \Deimos\CLI\Processor
{

    /**
     * @return Framework
     *
     * @throws \Deimos\CLI\Exceptions\Required
     * @throws \Deimos\CLI\Exceptions\UndefinedVariable
     */
    public function buildFramework()
    {
        return new Framework($this->cli);
    }

}

$proxy = new Runner($cli);
die($proxy->execute());