<?php

namespace srag\DataTableUI\SrTile\Implementation\Column\Formatter\Actions;

use Closure;
use ILIAS\UI\Component\Button\Shy;
use ILIAS\UI\Component\Component;
use ILIAS\UI\Component\Link\Standard as StandardInterface;
use ILIAS\UI\Implementation\Component\Button\Button;
use ILIAS\UI\Implementation\Component\Link\Standard;
use srag\DataTableUI\SrTile\Component\Column\Column;
use srag\DataTableUI\SrTile\Component\Column\Formatter\Actions\ActionsFormatter;
use srag\DataTableUI\SrTile\Component\Data\Row\RowData;
use srag\DataTableUI\SrTile\Component\Format\Format;
use srag\DataTableUI\SrTile\Component\Table;
use srag\DataTableUI\SrTile\Implementation\Column\Formatter\DefaultFormatter;

/**
 * Class ActionsDropdownFormatter
 *
 * @package srag\DataTableUI\SrTile\Implementation\Column\Formatter\Actions
 */
class ActionsDropdownFormatter extends DefaultFormatter implements ActionsFormatter
{

    /**
     * @inheritDoc
     */
    public function formatRowCell(Format $format, $actions, Column $column, RowData $row, string $table_id) : string
    {
        return self::output()->getHTML(self::dic()->ui()->factory()->dropdown()
            ->standard(array_map(function (Component $button) use ($format, $row, $table_id) : Component {
                if ($button instanceof Shy) {
                    return Closure::bind(function (Format $format, RowData $row, string $table_id) : Shy {
                        if (!empty($this->action) && empty($this->triggered_signals["click"])) {
                            $this->action = $format->getActionUrlWithParams($this->action, [Table::ACTION_GET_VAR => $row->getRowId()], $table_id);
                        }

                        return $this;
                    }, $button, Button::class)($format, $row, $table_id);
                }

                if ($button instanceof StandardInterface) {
                    return Closure::bind(function (Format $format, RowData $row, string $table_id) : Component {
                        if (!empty($this->action)) {
                            $this->action = $format->getActionUrlWithParams($this->action, [Table::ACTION_GET_VAR => $row->getRowId()], $table_id);
                        }

                        return $this;
                    }, $button, Standard::class)($format, $row, $table_id);
                }

                return $button;
            }, $actions))->withLabel($column->getTitle()));
    }
}
