<?php

namespace srag\Plugins\SrTile\ObjectLink;

use ilObjectFactory;
use ilSrTilePlugin;
use ilUtil;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Config\ConfigFormGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrTile\ObjectLink
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
{

    use DICTrait;
    use SrTileTrait;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var self
     */
    protected static $instance = null;


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Repository constructor
     */
    private function __construct()
    {

    }


    /**
     * @param Group $group
     */
    protected function deleteGroup(Group $group)/*:void*/
    {
        $group->delete();

        foreach ($this->getObjectLinks($group->getGroupId()) as $object_link) {
            $this->deleteObjectLink($object_link);
        }
    }


    /**
     * @param ObjectLink $object_link
     */
    public function deleteObjectLink(ObjectLink $object_link)/*:void*/
    {
        $object_link->delete();
    }


    /**
     * @internal
     */
    public function dropTables()/*:void*/
    {
        self::dic()->database()->dropTable(Group::TABLE_NAME, false);
        self::dic()->database()->dropTable(ObjectLink::TABLE_NAME, false);
    }


    /**
     * @return Factory
     */
    public function factory() : Factory
    {
        return Factory::getInstance();
    }


    /**
     * @param int $group_id
     *
     * @return Group|null
     */
    protected function getGroupById(int $group_id)/*:?Group*/
    {
        /**
         * @var Group|null $group
         */

        $group = Group::where([
            "group_id" => $group_id
        ])->first();

        return $group;
    }


    /**
     * @param int $obj_ref_id
     *
     * @return Group
     */
    public function getGroupByObject(int $obj_ref_id) : Group
    {
        $object_link = $this->getObjectLinkByObjRefId($obj_ref_id);

        if ($object_link !== null) {
            return $this->getGroupById($object_link->getGroupId());
        }

        $group = $this->factory()->newGroupInstance();
        $this->storeGroup($group);

        $object_link = $this->factory()->newObjectLinkInstance();
        $object_link->setGroupId($group->getGroupId());
        $object_link->setObjRefId($obj_ref_id);
        $this->storeObjectLink($object_link);

        return $group;
    }


    /**
     * @param int $obj_ref_id
     *
     * @return ObjectLink|null
     */
    protected function getObjectLinkByObjRefId(int $obj_ref_id)/*:?ObjectLink*/
    {
        /**
         * @var ObjectLink|null $object_link
         */

        $object_link = ObjectLink::where([
            "obj_ref_id" => $obj_ref_id
        ])->first();

        return $object_link;
    }


    /**
     * @param int $group_id
     * @param int $obj_ref_id
     *
     * @return ObjectLink|null
     */
    public function getObjectLink(int $group_id, int $obj_ref_id)/*:?ObjectLink*/
    {
        /**
         * @var ObjectLink|null $object_link
         */

        $object_link = ObjectLink::where([
            "group_id"   => $group_id,
            "obj_ref_id" => $obj_ref_id
        ])->first();

        return $object_link;
    }


    /**
     * @param int $group_id
     *
     * @return ObjectLink[]
     */
    public function getObjectLinks(int $group_id) : array
    {
        return ObjectLink::where([
            "group_id" => $group_id
        ])->orderBy("sort", "asc")->get();
    }


    /**
     * @param int $group_id
     * @param int $obj_ref_id
     *
     * @return array
     */
    public function getSelectableObjects(int $group_id, int $obj_ref_id) : array
    {
        return array_reduce(array_filter(self::dic()->tree()->getChildsByType(self::dic()->tree()->getParentId($obj_ref_id),
            self::dic()->objDataCache()->lookupType(self::dic()->objDataCache()->lookupObjId($obj_ref_id))), function (array $child) use ($group_id): bool {
            return ($this->getObjectLink($group_id, $child["child"]) === null);
        }), function (array $childs, array $child) : array {
            $language_text = self::srTile()->ilias()->metadata(ilObjectFactory::getInstanceByRefId($child["child"], false))->getLanguageText();
            if (!empty($language_text)) {
                $language_text = " (" . $language_text . ")";
            }

            $childs[$child["child"]] = $child["title"] . $language_text;

            return $childs;
        }, []);
    }


    /**
     * @param int $obj_ref_id
     *
     * @return ObjectLink[]
     */
    public function getShouldShowObjectLinks(int $obj_ref_id) : array
    {
        if (!self::srTile()->config()->getValue(ConfigFormGUI::KEY_ENABLED_OBJECT_LINKS)) {
            return [];
        }

        $object_links = $this->getObjectLinks($this->getGroupByObject($obj_ref_id)->getGroupId());

        $object_links = array_filter($object_links, function (ObjectLink $object_link) : bool {
            return self::srTile()->access()->hasVisibleAccess($object_link->getObjRefId());
        });

        if (count($object_links) < 2) {
            return [];
        }

        if (self::srTile()->config()->getValue(ConfigFormGUI::KEY_ENABLED_OBJECT_LINKS_ONCE_SELECT)) {
            if (!self::srTile()->access()->hasWriteAccess($obj_ref_id)) {
                if (!empty(array_filter($object_links, function (ObjectLink $object_link) : bool {
                    return (!empty(self::srTile()->ilias()->learningProgress(self::dic()->user())->getStatus($object_link->getObjRefId())));
                }))
                ) {
                    return [];
                }
            }
        }

        return $object_links;
    }


    /**
     * @internal
     */
    public function installTables()/*:void*/
    {
        Group::updateDB();
        ObjectLink::updateDB();
    }


    /**
     * @param ObjectLink $object_link
     */
    public function moveObjectLinkUp(ObjectLink $object_link)/*: void*/
    {
        $object_link->setSort($object_link->getSort() - 15);

        $this->storeObjectLink($object_link);

        $this->reSortObjectLinks($object_link->getGroupId());
    }


    /**
     * @param ObjectLink $object_link
     */
    public function moveObjectLinkDown(ObjectLink $object_link)/*: void*/
    {
        $object_link->setSort($object_link->getSort() + 15);

        $this->storeObjectLink($object_link);

        $this->reSortObjectLinks($object_link->getGroupId());
    }


    /**
     * @param int $group_id
     */
    protected function reSortObjectLinks(int $group_id)/*: void*/
    {
        $object_links = $this->getObjectLinks($group_id);

        $i = 1;
        foreach ($object_links as $object_link) {
            $object_link->setSort($i * 10);

            $this->storeObjectLink($object_link);

            $i++;
        }
    }


    /**
     * @param int $obj_ref_id
     *
     * @return bool
     */
    public function shouldShowObjectLink(int $obj_ref_id) : bool
    {
        $object_links = $this->getObjectLinks($this->getGroupByObject($obj_ref_id)->getGroupId());

        $object_links = array_filter($object_links, function (ObjectLink $object_link) : bool {
            return self::srTile()->access()->hasVisibleAccess($object_link->getObjRefId());
        });

        if (count($object_links) < 2) {
            return true;
        }

        $object_links2 = [];
        if (self::srTile()->config()->getValue(ConfigFormGUI::KEY_ENABLED_OBJECT_LINKS_ONCE_SELECT)) {
            if (!self::srTile()->access()->hasWriteAccess($obj_ref_id)) {
                $object_links2 = array_filter($object_links, function (ObjectLink $object_link) : bool {
                    return (!empty(self::srTile()->ilias()->learningProgress(self::dic()->user())->getStatus($object_link->getObjRefId())));
                });
            }
        }

        if (!empty($object_links2)) {
            return (current($object_links2)->getObjRefId() === $obj_ref_id);
        } else {
            return (current($object_links)->getObjRefId() === $obj_ref_id);
        }
    }


    /**
     * @param Group $group
     */
    protected function storeGroup(Group $group)/*:void*/
    {
        $group->store();
    }


    /**
     * @param ObjectLink $object_link
     * @param bool       $merge
     */
    public function storeObjectLink(ObjectLink $object_link, bool $merge = true)/*:void*/
    {
        if (empty($object_link->getObjectLinkId())) {
            if ($merge) {
                $object_link_ = $this->getObjectLinkByObjRefId($object_link->getObjRefId());
                if ($object_link_ !== null) {
                    foreach ($this->getObjectLinks($object_link->getGroupId()) as $object_link__) {
                        $object_link__->setGroupId($object_link_->getGroupId());
                        $this->storeObjectLink($object_link__);
                    }

                    //$this->deleteGroup($this->getGroupById($object_link->getGroupId()));

                    $this->reSortObjectLinks($object_link_->getGroupId());

                    ilUtil::sendInfo(self::plugin()->translate("merged", ObjectLinksGUI::LANG_MODULE), true);

                    return;
                }
            }

            if (empty($object_link->getSort())) {
                $object_link->setSort(((count($this->getObjectLinks($object_link->getGroupId())) + 1) * 10));
            }
        }
    }
}
