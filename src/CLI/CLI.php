<?php

namespace Deimos\CLI;

class CLI
{

    const VARIABLE_OPTIONAL = 1; // + default [null]
    const VARIABLE_REQUIRE  = 2; // throw new \In...
    const OPTION            = 3; // -v

    /**
     * @var CLIObject[]
     */
    protected $variables = [];

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
     * @return CLIObject
     */
    public function variable($fullName, $help = null)
    {
        $this->variables[$fullName] = new CLIObject($this->variables, $fullName, $help);

        return $this->variables[$fullName];
    }

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

            if (in_array($token, [' ', "\t"], true))
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
            else if ($token === '=')
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

        return $variables;
    }

}