<?php

namespace Deimos\CLI;

class Variable implements InterfaceVariable
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $required;

    /**
     * @var array
     */
    protected $value = [];

    /**
     * @var array
     */
    protected $defaultValue = [];

    /**
     * @var bool
     */
    protected $boolType;

    /**
     * @var array
     */
    protected $aliases = [];

    /**
     * Variable constructor.
     *
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param $name
     *
     * @return static
     */
    public function alias($name)
    {
        $this->aliases[] = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function aliases()
    {
        return $this->aliases;
    }

    /**
     * @return $this
     */
    public function boolType()
    {
        $this->boolType = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBoolType()
    {
        return (bool)$this->boolType;
    }

    /**
     * @return $this
     */
    public function required()
    {
        $this->required = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return (bool)$this->required;
    }

    /**
     * @param mixed $mixed
     *
     * @return $this
     */
    public function defaultValue($mixed)
    {
        $this->defaultValue = $mixed;

        return $this;
    }

    /**
     * @param $data
     */
    public function setValue($data)
    {
        $this->value = $data;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        if (!empty($this->value))
        {
            return $this->value;
        }

        return $this->defaultValue;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

}