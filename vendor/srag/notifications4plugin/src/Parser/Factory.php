<?php

namespace srag\Notifications4Plugin\SrTile\Parser;

use srag\DIC\SrTile\DICTrait;
use srag\Notifications4Plugin\SrTile\Utils\Notifications4PluginTrait;

/**
 * Class Factory
 *
 * @package srag\Notifications4Plugin\SrTile\Parser
 */
final class Factory implements FactoryInterface
{

    use DICTrait;
    use Notifications4PluginTrait;

    /**
     * @var FactoryInterface|null
     */
    protected static $instance = null;


    /**
     * Factory constructor
     */
    private function __construct()
    {

    }


    /**
     * @return FactoryInterface
     */
    public static function getInstance() : FactoryInterface
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @inheritDoc
     */
    public function twig() : twigParser
    {
        return new twigParser();
    }
}
