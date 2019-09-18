<?php

namespace srag\Plugins\SrTile\Notification\Notification;

use srag\Notifications4Plugin\SrTile\Notification\AbstractNotification;
use srag\Plugins\SrTile\Notification\Notification\Language\NotificationLanguage;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Notification
 *
 * @package srag\Plugins\SrTile\Notification\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Notification extends AbstractNotification
{

    use SrTileTrait;
    const TABLE_NAME = "ui_uihk_srtile_not";
    const LANGUAGE_CLASS_NAME = NotificationLanguage::class;
}
