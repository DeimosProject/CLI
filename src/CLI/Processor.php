<?php

namespace Deimos\CLI;

abstract class Processor extends Build
{

    /**
     * @return string
     */
    public function execute()
    {
        $this->initStorage();

        $this->self          = $this->{$this->method()}();
        $this->self->storage = &$this->storage;
        $this->self->index   = $this->index + 1;

        return $this->self->execute();
    }

}