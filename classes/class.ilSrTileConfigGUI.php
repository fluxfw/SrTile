<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\DevTools\SrTile\DevToolsCtrl;
use srag\DIC\SrTile\DICTrait;
use srag\Notifications4Plugin\SrTile\Notification\NotificationsCtrl;
use srag\Plugins\SrTile\Config\ConfigCtrl;
use srag\Plugins\SrTile\Template\TemplatesConfigGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class ilSrTileConfigGUI
 *
 * @ilCtrl_isCalledBy srag\Notifications4Plugin\SrTile\Notification\NotificationsCtrl: ilSrTileConfigGUI
 * @ilCtrl_isCalledBy srag\DevTools\SrTile\DevToolsCtrl: ilSrTileConfigGUI
 */
class ilSrTileConfigGUI extends ilPluginConfigGUI
{

    use DICTrait;
    use SrTileTrait;

    const CMD_CONFIGURE = "configure";
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;


    /**
     * ilSrTileConfigGUI constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function performCommand(/*string*/ $cmd) : void
    {
        self::srTile();

        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(ConfigCtrl::class):
                self::dic()->ctrl()->forwardCommand(new ConfigCtrl());
                break;

            case strtolower(DevToolsCtrl::class):
                self::dic()->ctrl()->forwardCommand(new DevToolsCtrl($this, self::plugin()));
                break;

            case strtolower(NotificationsCtrl::class):
                self::dic()->tabs()->activateTab(NotificationsCtrl::TAB_NOTIFICATIONS);
                self::dic()->ctrl()->forwardCommand(new NotificationsCtrl());
                break;

            case strtolower(TemplatesConfigGUI::class):
                self::dic()->ctrl()->forwardCommand(new TemplatesConfigGUI());
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_CONFIGURE:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     *
     */
    protected function configure() : void
    {
        self::dic()->ctrl()->redirectByClass(ConfigCtrl::class, ConfigCtrl::CMD_CONFIGURE);
    }


    /**
     *
     */
    protected function setTabs() : void
    {
        ConfigCtrl::addTabs();

        TemplatesConfigGUI::addTabs();

        self::dic()->tabs()->addTab(NotificationsCtrl::TAB_NOTIFICATIONS, self::plugin()->translate("notifications", NotificationsCtrl::LANG_MODULE), self::dic()->ctrl()
            ->getLinkTargetByClass(NotificationsCtrl::class, NotificationsCtrl::CMD_LIST_NOTIFICATIONS));

        DevToolsCtrl::addTabs(self::plugin());

        self::dic()->locator()->addItem(ilSrTilePlugin::PLUGIN_NAME, self::dic()->ctrl()->getLinkTarget($this, self::CMD_CONFIGURE));
    }
}
