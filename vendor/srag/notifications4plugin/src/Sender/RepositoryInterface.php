<?php

namespace srag\Notifications4Plugin\SrTile\Sender;

use srag\Notifications4Plugin\SrTile\Exception\Notifications4PluginException;
use srag\Notifications4Plugin\SrTile\Notification\NotificationInterface;

/**
 * Interface RepositoryInterface
 *
 * @package srag\Notifications4Plugin\SrTile\Sender
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface RepositoryInterface
{

    /**
     * @internal
     */
    public function dropTables()/* : void*/;


    /**
     * @return FactoryInterface
     */
    public function factory() : FactoryInterface;


    /**
     * @internal
     */
    public function installTables()/* : void*/;


    /**
     * @param Sender                $sender   A concrete srNotificationSender object, e.g. srNotificationMailSender
     * @param NotificationInterface $notification
     * @param array                 $placeholders
     * @param string|null           $language Omit to choose the default language
     *
     * @throws Notifications4PluginException
     */
    public function send(Sender $sender, NotificationInterface $notification, array $placeholders = [], /*?string*/ $language = null)/* : void*/;
}
