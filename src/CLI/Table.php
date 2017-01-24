<?php
/**
 * This file is part of the PHPLucidFrame library.
 * The class makes you easy to build console style tables
 *
 * @package     PHPLucidFrame\Console
 * @since       PHPLucidFrame v 1.12.0
 * @copyright   Copyright (c), PHPLucidFrame.
 * @author      Sithu K. <cithukyaw@gmail.com>
 * @link        http://phplucidframe.github.io
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE
 */

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
     * @var bool
     */
    protected $border = true;

    /**
     * @var int
     */
    protected $padding = 1;

    /**
     * @var int
     */
    protected $indent = 0;

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
     * @return mixed|null
     */
    public function getHeaders()
    {
        return isset($this->data[self::HEADER_INDEX]) ? $this->data[self::HEADER_INDEX] : null;
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
     * @return $this
     */
    public function showBorder()
    {
        $this->border = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function hideBorder()
    {
        $this->border = false;

        return $this;
    }

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setPadding($value = 1)
    {
        $this->padding = $value;

        return $this;
    }

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setIndent($value = 0)
    {
        $this->indent = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $this->calculateColumnWidth();

        $output = $this->border ? $this->getBorderLine() : '';
        foreach ($this->data as $y => $row)
        {
            foreach ($row as $x => $cell)
            {
                $output .= $this->getCellOutput($x, $row);
            }

            $output .= "\n";

            if ($y === self::HEADER_INDEX)
            {
                $output .= $this->getBorderLine();
            }
        }
        $output .= $this->border ? $this->getBorderLine() : '';

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

        if ($this->border)
        {
            $output .= '+';
        }

        $output .= "\n";

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
        $cell    = $row ? $row[$index] : '-';
        $width   = $this->columnWidths[$index];
        $padding = str_repeat($row ? ' ' : '-', $this->padding);

        $output = '';

        if ($index === 0)
        {
            $output .= str_repeat(' ', $this->indent);
        }

        if ($this->border)
        {
            $output .= $this->well($row);
        }

        $output .= $padding;
        $output .= str_pad($cell, $width, $row ? ' ' : '-');
        $output .= $padding;

        if (($index === count($row) - 1) && $this->border)
        {
            $output .= $this->well($row);
        }

        return $output;
    }

    /**
     * @param $row
     *
     * @return string
     */
    protected function well($row)
    {
        return $row ? '|' : '+';
    }

    /**
     * @return array
     */
    private function calculateColumnWidth()
    {
        foreach ($this->data as $y => $row)
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

        return $this->columnWidths;
    }

}
