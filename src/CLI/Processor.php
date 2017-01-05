<?php

namespace Deimos\CLI;

class Processor implements \Iterator
{

    /**
     * @var CLI[]
     */
    protected $processors = [];

    /**
     * @var array
     */
    protected $args;

    /**
     * Processor constructor.
     *
     * @param array $args
     */
    public function __construct(array $args)
    {
        $this->args = $args;
    }

    /**
     * @param string $class
     *
     * @return CLI
     *
     * @throws \InvalidArgumentException
     */
    public function register($class)
    {
        $cli = $this->processors[] = new CLI($this->args);

        return $cli->setClass($class);
    }

    public function build()
    {
        // run cli + run register cli -> processor -> output
    }



    // iterator

    /**
     * @inheritdoc
     */
    public function current()
    {
        return current($this->processors);
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        next($this->processors);
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return key($this->processors);
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        return isset($this->processors[$this->key()]);
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        reset($this->processors);
    }

}