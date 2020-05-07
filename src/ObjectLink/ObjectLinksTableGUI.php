<?php

namespace srag\Plugins\SrTile\ObjectLink;

use ilSrTilePlugin;
use srag\CustomInputGUIs\SrTile\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\SrTile\TableGUI\TableGUI;
use srag\CustomInputGUIs\SrTile\Waiter\Waiter;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class ObjectLinksTableGUI
 *
 * @package srag\Plugins\SrTile\ObjectLink
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ObjectLinksTableGUI extends TableGUI
{

    use SrTileTrait;

    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const LANG_MODULE = ObjectLinksGUI::LANG_MODULE;


    /**
     * ObjectLinksTableGUI constructor
     *
     * @param ObjectLinksGUI $parent
     * @param string         $parent_cmd
     */
    public function __construct(ObjectLinksGUI $parent, string $parent_cmd)
    {
        parent::__construct($parent, $parent_cmd);
    }


    /**
     * @inheritDoc
     *
     * @param ObjectLink $object_link
     */
    protected function getColumnValue(/*string*/ $column, /*ObjectLink*/ $object_link, /*int*/ $format = self::DEFAULT_FORMAT) : string
    {
        switch ($column) {
            case "title":
                $column = htmlspecialchars($object_link->getObject()->getTitle());
                break;

            case "language":
                $language_flag = "";
                if ($this->parent_obj->getParent()->getTile()->getShowLanguageFlag() === Tile::SHOW_TRUE) {
                    $language_flag = self::srTile()->ilias()->metadata($object_link->getObject())->getLanguageImage();
                }

                $column = $language_flag . htmlspecialchars(self::srTile()->ilias()->metadata($object_link->getObject())->getLanguageText());
                break;

            default:
                $column = htmlspecialchars(Items::getter($object_link, $column));
                break;
        }

        return strval($column);
    }


    /**
     * @inheritDoc
     */
    public function getSelectableColumns2() : array
    {
        $columns = [
            "title"    => [
                "id"      => "title",
                "default" => true,
                "sort"    => false,
                "txt"     => $this->txt("object")
            ],
            "language" => [
                "id"      => "language",
                "default" => true,
                "sort"    => false,
                "txt"     => $this->txt("language")
            ]
        ];

        return $columns;
    }


    /**
     * @inheritDoc
     */
    protected function initColumns()/*: void*/
    {
        $this->addColumn("");

        parent::initColumns();

        $this->addColumn($this->txt("actions"));
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {
        self::dic()->toolbar()->addComponent(self::dic()->ui()->factory()->button()->standard($this->txt("add_object_link"), self::dic()->ctrl()
            ->getLinkTargetByClass(ObjectLinkGUI::class, ObjectLinkGUI::CMD_ADD_OBJECT_LINK)));
    }


    /**
     * @inheritDoc
     */
    protected function initData()/*: void*/
    {
        $this->setExternalSegmentation(true);
        $this->setExternalSorting(true);

        $this->setData(self::srTile()->objectLinks()->getObjectLinks($this->parent_obj->getGroup()->getGroupId()));
    }


    /**
     * @inheritDoc
     */
    protected function initFilterFields()/*: void*/
    {
        $this->filter_fields = [];
    }


    /**
     * @inheritDoc
     */
    protected function initId()/*: void*/
    {
        $this->setId(ilSrTilePlugin::PLUGIN_ID . "_object_links");
    }


    /**
     * @inheritDoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt("object_links"));
    }


    /**
     * @param ObjectLink $object_link
     */
    protected function fillRow(/*ObjectLink*/ $object_link)/*: void*/
    {
        self::dic()->ctrl()->setParameterByClass(ObjectLinkGUI::class, ObjectLinkGUI::GET_PARAM_OBJ_REF_ID, $object_link->getObjRefId());

        $this->tpl->setCurrentBlock("column");
        $this->tpl->setVariable("COLUMN", self::output()->getHTML([
            self::dic()->ui()->factory()->glyph()->sortAscending()->withAdditionalOnLoadCode(function (string $id) : string {
                Waiter::init(Waiter::TYPE_WAITER);

                return '
            $("#' . $id . '").click(function () {
                il.waiter.show();
                var row = $(this).parent().parent();
                $.ajax({
                    url: ' . json_encode(self::dic()
                        ->ctrl()
                        ->getLinkTargetByClass(ObjectLinkGUI::class, ObjectLinkGUI::CMD_MOVE_OBJECT_LINK_UP, "", true)) . ',
                    type: "GET"
                 }).always(function () {
                    il.waiter.hide();
               }).success(function() {
                    row.insertBefore(row.prev());
                });
            });';
            }),
            self::dic()->ui()->factory()->glyph()->sortDescending()->withAdditionalOnLoadCode(function (string $id) : string {
                return '
            $("#' . $id . '").click(function () {
                il.waiter.show();
                var row = $(this).parent().parent();
                $.ajax({
                    url: ' . json_encode(self::dic()
                        ->ctrl()
                        ->getLinkTargetByClass(ObjectLinkGUI::class, ObjectLinkGUI::CMD_MOVE_OBJECT_LINK_DOWN, "", true)) . ',
                    type: "GET"
                }).always(function () {
                    il.waiter.hide();
                }).success(function() {
                    row.insertAfter(row.next());
                });
        });';
            })
        ]));
        $this->tpl->parseCurrentBlock();

        parent::fillRow($object_link);

        $actions = [];
        if ($object_link->getObjRefId() !== $this->parent_obj->getParent()->getTile()->getObjRefId()) {
            $actions[] = self::dic()->ui()->factory()->link()->standard($this->txt("remove_object_link"), self::dic()->ctrl()
                ->getLinkTargetByClass(ObjectLinkGUI::class, ObjectLinkGUI::CMD_REMOVE_OBJECT_LINK));
        }

        $this->tpl->setVariable("COLUMN", self::output()->getHTML(self::dic()->ui()->factory()->dropdown()->standard($actions)->withLabel($this->txt("actions"))));
    }
}
