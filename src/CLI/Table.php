<?php

namespace Deimos\CLI;

class Table
{

    /**
     * const
     */
    const HEADER_INDEX = -1;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var int
     */
    private $rowIndex = -1;

    /**
     * @var array
     */
    private $columnWidths = [];

    /**
     * @param array $content
     *
     * @return $this
     */
    public function setHeaders(array $content)
    {
        $this->data[self::HEADER_INDEX] = $content;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function addRow(array $data)
    {
        $this->rowIndex++;

        foreach ($data as $col => $content)
        {
            $this->data[$this->rowIndex][$col] = $content;
        }

        return $this;
    }

    /**
     * @param array $row
     * @param       $y
     * @param       $output
     */
    private function output(array $row, $y, &$output)
    {

        foreach ($row as $x => $cell)
        {
            $output .= $this->getCellOutput($x, $row);
        }

        $output .= PHP_EOL;

        if ($y === self::HEADER_INDEX)
        {
            $output .= $this->getBorderLine();
        }

    }

    /**
     * @return string
     */
    public function __toString()
    {
        $this->calculateColumnWidth();

        $output = $this->getBorderLine();

        foreach ($this->data as $y => $row)
        {
            $this->output($row, $y, $output);
        }

        $output .= $this->getBorderLine();

        return $output;
    }

    /**
     * @return string
     */
    private function getBorderLine()
    {
        $output      = '';
        $columnCount = count($this->data[0]);

        for ($col = 0; $col < $columnCount; $col++)
        {
            $output .= $this->getCellOutput($col);
        }

        return $output . "+\n";
    }

    private function cellOutput($row, array $options)
    {
        $output =
            $this->well($row) .
            $options['padding'] .
            str_pad($options['cell'], $options['width'], $row ? ' ' : ' - ') .
            $options['padding'];

        if ($options['index'] === count($row) - 1)
        {
            $output .= $this->well($row);
        }

        return $output;
    }

    /**
     * @param      $index
     * @param null $row
     *
     * @return string
     */
    private function getCellOutput($index, $row = null)
    {
        return $this->cellOutput($row, [
            'cell'    => $row ? $row[$index] : ' - ',
            'width'   => $this->columnWidths[$index],
            'padding' => str_repeat($row ? ' ' : ' - ', 1),
            'index'   => $index,
        ]);
    }

    /**
     * @param $row
     *
     * @return string
     */
    protected function well($row)
    {
        return $row ? ' | ' : ' + ';
    }

    /**
     * @param array $row
     */
    private function calculateRow(array $row)
    {
        foreach ($row as $x => $col)
        {
            $result = strlen($col);

            if (!isset($this->columnWidths[$x]) ||
                ($result > $this->columnWidths[$x])
            )
            {
                $this->columnWidths[$x] = $result;
            }
        }
    }

    /**
     * @return array
     */
    private function calculateColumnWidth()
    {
        /**
         * @var array $row
         */
        foreach ($this->data as $y => $row)
        {
            $this->calculateRow($row);
        }

        return $this->columnWidths;
    }

}
