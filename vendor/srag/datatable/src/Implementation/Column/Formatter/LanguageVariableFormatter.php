<?php

namespace srag\DataTableUI\SrTile\Implementation\Column\Formatter;

use srag\DataTableUI\SrTile\Component\Column\Column;
use srag\DataTableUI\SrTile\Component\Data\Row\RowData;
use srag\DataTableUI\SrTile\Component\Format\Format;

/**
 * Class LanguageVariableFormatter
 *
 * @package srag\DataTableUI\SrTile\Implementation\Column\Formatter
 */
class LanguageVariableFormatter extends DefaultFormatter
{

    /**
     * @var string
     */
    protected $prefix;


    /**
     * @inheritDoc
     *
     * @param string $prefix
     */
    public function __construct(string $prefix)
    {
        parent::__construct();

        $this->prefix = $prefix;
    }


    /**
     * @inheritDoc
     */
    public function formatRowCell(Format $format, $value, Column $column, RowData $row, string $table_id) : string
    {
        $value = strval($value);

        if (!empty($value)) {
            if (!empty($this->prefix)) {
                $value = rtrim($this->prefix, "_") . "_" . $value;
            }

            $value = self::dic()->language()->txt($value);
        }

        return parent::formatRowCell($format, $value, $column, $row, $table_id);
    }
}
