<?php

namespace Deimos\CLI;

use Deimos\CLI\Exceptions\CLIRun;
use Deimos\CLI\Exceptions\Required;
use Deimos\CLI\Exceptions\UndefinedVariable;

class CLI
{

    /**
     * @var array
     */
    protected $argv;

    /**
     * @var array
     */
    protected $variables = [];

    /**
     * @var array
     */
    protected $aliases;

    /**
     * @var array
     */
    protected $requiredList;

    /**
     * @var array
     */
    protected $storage;

    /**
     * @var array
     */
    protected $commands = [];

    /**
     * @var bool
     */
    protected $run;

    /**
     * CLI constructor.
     *
     * @param array $argv
     */
    public function __construct(array $argv)
    {
        $this->argv = $argv;

        if (!empty($this->argv))
        {
            // remove element [0] *.php
            array_shift($this->argv);
        }
    }

    /**
     * @param string $name
     *
     * @return InterfaceVariable
     */
    public function variable($name)
    {
        return ($this->variables[$name] = new Variable($name));
    }

    /**
     * @return string
     */
    protected function init()
    {
        $this->aliases      = [];
        $this->requiredList = [];

        /**
         * @var $variable Variable
         */
        foreach ($this->variables as $variable)
        {
            if ($variable->isRequired())
            {
                $this->requiredList[] = $variable->name();
            }

            // init aliases
            foreach ($variable->aliases() as $alias)
            {
                $this->aliases[$alias] = $variable;
            }
        }

        return implode(' ', $this->argv);
    }

    /**
     * @param $data
     *
     * @return array|mixed
     */
    protected function value($data)
    {
        return is_array($data) && isset($data[1]) ? $data[1] : $data;
    }

    /**
     * @param array $array
     */
    protected function initStorage(array &$array)
    {
        foreach ($array as $item)
        {
            $value = $this->value($item);

            if (in_array($value, ['-', '--'], false))
            {
                break;
            }

            $data = array_shift($array);

            if ($value !== ' ')
            {
                $this->storage[] = $this->value($data);
            }
        }
    }

    /**
     * @param array $array
     *
     * @throws UndefinedVariable
     */
    protected function initVariable(array &$array)
    {
        $isAlias = false;
        $isKey   = false;
        $key     = null;

        foreach ($array as $item)
        {
            $item  = array_shift($array);
            $value = $this->value($item);

            if ($value === ' ')
            {
                continue;
            }

            if (!$isKey && in_array($value, ['-', '--'], true))
            {
                $isAlias = $value === '-';
                $isKey   = true;
                continue;
            }

            if ($isKey)
            {
                $key   = $value;
                $isKey = false;

                if ($isAlias && !isset($this->aliases[$key]))
                {
                    throw new UndefinedVariable('Not found alias \'' . $key . '\'');
                }

                $this->commands[$key] = [];
                continue;
            }

            $stringValue = '';

            while ($value !== ' ')
            {
                if ($value === null)
                {
                    break;
                }

                $stringValue .= $value;

                $item  = array_shift($array);
                $value = $this->value($item);
            }

            if (!empty($stringValue))
            {
                $this->commands[$key][] = $stringValue;
            }

        }
    }

    /**
     * @return bool
     * @throws Required
     */
    protected function initRequired()
    {
        foreach ($this->requiredList as $name)
        {
            /**
             * @var Variable $variable
             * @var array    $aliasList
             */
            $variable  = $this->variables[$name];
            $aliasList = $variable->aliases();

            if (!isset($this->commands[$name]))
            {
                $keys = array_keys($this->commands);
                foreach ($aliasList as $alias)
                {
                    if (in_array($alias, $keys, true))
                    {
                        continue 2;
                    }
                }

                throw new Required('Not found required argument \'' . $name . '\'');
            }

            continue;

        }

        return true;
    }

    /**
     * @throws UndefinedVariable
     */
    protected function initUndefined()
    {
        foreach ($this->commands as $command => &$data)
        {
            if (!isset($this->variables[$command]) && !isset($this->aliases[$command]))
            {
                throw new UndefinedVariable('Not found variable \'' . $command . '\'');
            }
        }
    }

    protected function loadCommand(array $commands)
    {
        $this->commands = [];

        foreach ($commands as $name => $value)
        {
            /**
             * @var Variable $variable
             */
            $variable = $this->aliases[$name];

            if ($variable === null)
            {
                $variable = $this->variables[$name];
            }

            if (!empty($value))
            {
                $variable->setValue($value);
            }

            $this->commands[$variable->name()] =
                $variable->isBoolType()
                    ?: $variable->value();
        }

        foreach ($this->variables as $variable)
        {
            if (!isset($this->commands[$variable->name()]))
            {
                $this->commands[$variable->name()] =
                    $variable->isBoolType() ? false :
                        $variable->value();
            }
        }

        $this->run = true;
    }

    public function &commands()
    {
        if (!$this->run)
        {
            throw new CLIRun('Start the run method');
        }

        return $this->commands;
    }

    /**
     * @return array
     */
    public function storage()
    {
        return $this->storage;
    }

    /**
     * @param $name
     *
     * @return mixed
     *
     * @throws CLIRun
     */
    public function __get($name)
    {
        $commands = &$this->commands();

        return $commands[$name];
    }

    /**
     * @param $name
     * @param $value
     *
     * @throws \InvalidArgumentException
     */
    final public function __set($name, $value)
    {
        throw new \InvalidArgumentException(__METHOD__);
    }

    /**
     * @param $name
     *
     * @return bool
     *
     * @throws CLIRun
     */
    public function __isset($name)
    {
        $commands = &$this->commands();

        return isset($commands[$name]);
    }

    /**
     * @throws Required
     * @throws UndefinedVariable
     * @throws \InvalidArgumentException
     */
    public function run()
    {
        if ($this->run)
        {
            throw new \InvalidArgumentException(__METHOD__);
        }

        $string    = $this->init();
        $tokenizer = new Tokenizer($string);

        $storage = $tokenizer->run();
        $this->initStorage($storage);
        $this->initVariable($storage);
        $this->initRequired();
        $this->initUndefined();
        $this->loadCommand($this->commands);
    }

}