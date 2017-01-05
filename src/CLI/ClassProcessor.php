<?php

namespace Deimos\CLI;

use Deimos\Builder\Builder;

class ClassProcessor extends Builder
{

    /**
     * @var CLI
     */
    protected $cli;

    /**
     * CLIClass constructor.
     *
     * @param CLI $cli
     */
    public function __construct(CLI $cli)
    {
        $this->cli = $cli;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function methodName($name)
    {
        return 'action' . ucfirst($name);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function instance($name)
    {
        return parent::instance($name);
    }

}