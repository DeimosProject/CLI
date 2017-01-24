<?php

namespace Deimos\CLI;

abstract class Controller extends Build
{

    protected function method()
    {
        $actionName = $this->at();

        return 'command' . ucfirst($actionName);
    }

    /**
     * @return string
     */
    public function execute()
    {
        return $this->{$this->method()}();
    }

}