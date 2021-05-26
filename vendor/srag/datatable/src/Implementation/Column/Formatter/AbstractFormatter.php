<?php

namespace srag\DataTableUI\SrTile\Implementation\Column\Formatter;

use srag\DataTableUI\SrTile\Component\Column\Formatter\Formatter;
use srag\DataTableUI\SrTile\Implementation\Utils\DataTableUITrait;
use srag\DIC\SrTile\DICTrait;

/**
 * Class AbstractFormatter
 *
 * @package srag\DataTableUI\SrTile\Implementation\Column\Formatter
 */
abstract class AbstractFormatter implements Formatter
{

    use DICTrait;
    use DataTableUITrait;

    /**
     * AbstractFormatter constructor
     */
    public function __construct()
    {

    }
}
