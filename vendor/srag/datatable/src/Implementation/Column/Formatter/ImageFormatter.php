<?php

namespace srag\DataTableUI\SrTile\Implementation\Column\Formatter;

use srag\DataTableUI\SrTile\Component\Column\Column;
use srag\DataTableUI\SrTile\Component\Data\Row\RowData;
use srag\DataTableUI\SrTile\Component\Format\Format;

/**
 * Class ImageFormatter
 *
 * @package srag\DataTableUI\SrTile\Implementation\Column\Formatter
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ImageFormatter extends DefaultFormatter
{

    /**
     * @inheritDoc
     */
    public function formatRowCell(Format $format, $image, Column $column, RowData $row, string $table_id) : string
    {
        if (!empty($image)) {
            return self::output()->getHTML(self::dic()->ui()->factory()->image()->responsive($image, ""));
        } else {
            return "";
        }
    }
}
