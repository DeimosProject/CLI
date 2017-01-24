<?php

namespace Deimos\CLI;

interface Execute
{
    /**
     * @return mixed
     */
    public function execute();

    /**
     * @return mixed
     */
    public function at();
}