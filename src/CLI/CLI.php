<?php

namespace Deimos\CLI;

class CLI
{

    const VARIABLE_OPTIONAL = 1; // + default [null]
    const VARIABLE_REQUIRE  = 2; // throw new \In...
    const OPTION            = 3; // -v

    /**
     * @var ClassProcessor
     */
    protected $class;

    /**
     * @var SelfObject[]
     */
    protected $variables = [];

    /**
     * @var SelfObject[]
     */
    protected $aliases = [];

    /**
     * @var string
     */
    protected $command;

    /**
     * @var string
     */
    protected $normalizeCommand;

    /**
     * @var array
     */
    protected $originTokens;

    /**
     * @var array
     */
    protected $tokens;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * cli constructor.
     *
     * @param array $argv
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $argv)
    {
        array_shift($argv);
        $this->command = implode(' ', $argv);
    }

    /**
     * normalize command
     *
     * @return string
     */
    protected function normalizeCommand()
    {
        if (!$this->normalizeCommand)
        {
            $this->normalizeCommand = preg_replace('~(--?[\w:]+)(\s+|=)(\w)~', '$1=$3', $this->command);
            $this->normalizeCommand = preg_replace('~\s+=\s+~', '=', $this->normalizeCommand);
        }

        return $this->normalizeCommand;
    }

    /**
     * @return array
     */
    protected function originTokens()
    {
        if (!$this->originTokens)
        {
            $this->originTokens = token_get_all('<?php ' . $this->normalizeCommand());
            array_shift($this->originTokens);
        }

        return $this->originTokens;
    }

    /**
     * @param string $fullName
     * @param string $help
     *
     * @return SelfObject
     */
    public function variable($fullName, $help = null)
    {
        $this->variables[$fullName] = new SelfObject($this->variables, $this->aliases, $fullName, $help);

        return $this->variables[$fullName];
    }

    /**
     * @return SelfObject[]
     */
    public function variables()
    {
        return $this->variables;
    }

    /**
     * @return SelfObject[]
     */
    public function aliases()
    {
        return $this->aliases;
    }

    /**
     * @param $string
     *
     * @return bool
     */
    protected function hasEqual($string)
    {
        return $string === '=';
    }

    /**
     * @param $string
     *
     * @return bool
     */
    protected function hasSpace($string)
    {
        return in_array($string, [' ', "\t"], true);
    }

    /**
     * @param array $variables
     *
     * @return array
     */
    protected function listBuild(&$variables)
    {
        $list = [];
        foreach ($variables as $key => $variable)
        {
            if (is_int($key))
            {
                if (empty($list['list']))
                {
                    $list['list'] = [$variable];
                }
                else
                {
                    $list['list'][] = $variable;
                }
            }
            else
            {
                $list[$key] = $variable;
            }
        }

        return $list;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function isKey($key)
    {
        return $key{0} === '-';
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function isVariable($key)
    {
        return $this->isKey($key) && $key{1} === '-';
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function isAlias($key)
    {
        return $this->isKey($key) && $key{1} !== '-';
    }

    /**
     * @param $class
     *
     * @return array
     */
    private function classParent($class)
    {
        return class_parents($class);
    }

    /**
     * @param $class
     *
     * @return bool
     */
    private function hasCLIClass($class)
    {
        return in_array(
            ClassProcessor::class,
            $this->classParent($class),
            true
        );
    }

    /**
     * @param string $class
     *
     * @return self
     *
     * @throws \InvalidArgumentException
     */
    public function setClass($class)
    {
        if (!$this->hasCLIClass($class))
        {
            throw new \InvalidArgumentException('\'' . ClassProcessor::class . '\' not found');
        }

        $this->class = $class;

        return $this;
    }

    /**
     * @return array
     */
    public function build()
    {
        $this->attributes = [];
        $variables        = &$this->attributes;

        $iterator = 0;
        $equal    = false;
        $value    = '';

        $this->tokens = $this->originTokens();
        $tokens       = &$this->tokens;

        foreach ($tokens as $key => $token)
        {
            $token = is_array($token) ? $token[1] : $token;

            if ($this->hasSpace($token))
            {
                if ($equal)
                {
                    $variable = $variables[$iterator];
                    unset($variables[$iterator]);

                    $variables[$variable] = $value;
                }

                $value = '';
                $equal = false;
                $iterator++;
                continue;
            }
            else if ($this->hasEqual($token))
            {
                $equal = true;
                continue;
            }

            if ($equal)
            {
                $value .= $token;
                continue;
            }

            $variables[$iterator] .= $token;
        }

        if ($equal)
        {
            $variable = $variables[$iterator];
            unset($variables[$iterator]);

            $variables[$variable] = $value;
        }

        return $this->listBuild($variables);
    }

}