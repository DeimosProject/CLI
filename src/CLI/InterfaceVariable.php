<?php

namespace Deimos\CLI;

interface InterfaceVariable
{

    /**
     * @param $name
     *
     * @return static
     */
    public function alias($name);

    /**
     * @param string $name
     *
     * @return static
     */
    public function defaultValue($name);

    /**
     * @return static
     */
    public function required();

    /**
     * @return static
     */
    public function boolType();

}