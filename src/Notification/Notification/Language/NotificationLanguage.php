<?php

namespace srag\Plugins\SrTile\Notification\Notification\Language;

use srag\Notifications4Plugin\SrTile\Notification\Language\AbstractNotificationLanguage;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class NotificationLanguage
 *
 * @package srag\Plugins\SrTile\Notification\Notification\Language
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class NotificationLanguage extends AbstractNotificationLanguage
{

    use SrTileTrait;
    const TABLE_NAME = "ui_uihk_srtile_not_lan";
}
