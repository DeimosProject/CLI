<?php

namespace Deimos\CLI;

class CLIObject
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
     * @param array $variables
     * @param       $fullName
     * @param       $help
     */
    public function __construct(array &$variables, $fullName, $help)
    {
        $this->variables = &$variables;
        $this->fullName  = $fullName;
        $this->help      = $help;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function alias($name)
    {
        $this->variables['-' . $name] = $this;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return !$this->isOptional();
    }

    /**
     * @return bool
     */
    public function isOptional()
    {
        return !$this->required;
    }

    /**
     * set required = true
     *
     * @return $this
     */
    public function required()
    {
        $this->required = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function optional()
    {
        $this->required = false;

        return $this;
    }

}