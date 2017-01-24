<?php

namespace Deimos\CLI;

abstract class Build implements Execute
{

    /**
     * @var Execute
     */
    protected $self;

    /**
     * @var int $index
     */
    protected $index = 0;

    /**
     * @var CLI
     */
    protected $cli;

    /**
     * @var array
     */
    protected $storage;

    /**
     * Processor constructor.
     *
     * @param CLI $cli
     *
     * @throws Exceptions\Required
     * @throws Exceptions\UndefinedVariable
     * @throws \InvalidArgumentException
     */
    public function __construct(CLI $cli)
    {
        $this->self      = $this;
        $this->self->cli = $cli->run();
    }

    /**
     * @param $storage
     */
    public function setStorage($storage)
    {
        if (is_array($storage))
        {
            $this->self->storage = $storage;
        }
    }

    /**
     * @param $index
     */
    public function setIndex($index)
    {
        if ($index)
        {
            $this->self->index = $index;
        }
    }

    /**
     * init storage
     */
    protected function initStorage()
    {
        if ($this->self->storage === null)
        {
            $item                = implode($this->cli->storage());
            $this->self->storage = explode(':', $item);
        }
    }

    /**
     * @return mixed
     */
    public function at()
    {
        $this->initStorage();

        if (!isset($this->self->storage[$this->self->index]))
        {
            return 'default';
        }

        return $this->self->storage[$this->self->index];
    }

    /**
     * @return mixed
     */
    protected function method()
    {
        $storage = $this->at();

        return 'build' . ucfirst($storage);
    }

}