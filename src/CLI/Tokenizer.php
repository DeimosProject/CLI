<?php

namespace Deimos\CLI;

class Tokenizer
{

    /**
     * @var string
     */
    protected $data;

    /**
     * Tokenizer constructor.
     *
     * @param string $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function run()
    {
        $rows = token_get_all('<?php ' . $this->data);
        array_shift($rows);

        return $rows;
    }

}