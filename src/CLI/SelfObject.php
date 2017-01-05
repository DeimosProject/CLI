<?php

namespace Deimos\CLI;

class SelfObject
{

    /**
     * @var self[]
     */
    protected $variables;

    /**
     * @var string
     */
    protected $fullName;

    /**
     * @var self[]
     */
    protected $aliases;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var bool
     */
    protected $required;

    /**
     * @var string
     */
    protected $help;

    /**
     * cliObject constructor.
     *
     * @param array  $variables
     * @param array  $aliases
     * @param string $fullName
     * @param string $help
     */
    public function __construct(array &$variables, array &$aliases, $fullName, $help)
    {
        $this->variables = &$variables;
        $this->aliases   = &$aliases;
        $this->fullName  = $fullName;
        $this->help      = $help;
    }

    /**
     * @param $name
     *
     * @return self
     */
    public function alias($name)
    {
        $this->aliases[$name] = $this;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return bool
     */
    public function isOptional()
    {
        return !$this->isRequired();
    }

    /**
     * set required = true
     *
     * @return self
     */
    public function required()
    {
        $this->required = true;

        return $this;
    }

    /**
     * @return self
     */
    public function optional()
    {
        $this->required = false;

        return $this;
    }

}