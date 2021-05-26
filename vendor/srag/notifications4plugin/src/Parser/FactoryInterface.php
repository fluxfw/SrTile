<?php

namespace srag\Notifications4Plugin\SrTile\Parser;

/**
 * Interface FactoryInterface
 *
 * @package srag\Notifications4Plugin\SrTile\Parser
 */
interface FactoryInterface
{

    /**
     * @return twigParser
     */
    public function twig() : twigParser;
}
