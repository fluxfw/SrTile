<?php

namespace srag\Plugins\SrTile\Certificate;

use ilCertificatePlugin;
use ilCourseParticipants;
use ilLPStatus;
use ilObjCourseGUI;
use ilObjUser;
use ilRepositoryGUI;
use ilSAHSPresentationGUI;
use ilSrTilePlugin;
use ilSrTileUIHookGUI;
use ilUIPluginRouterGUI;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;
use srCertificate;
use srCertificateDefinition;
use srCertificateUserGUI;

/**
 * Class Certificates
 *
 * @package srag\Plugins\SrTile\Certificate
 */
class Certificates
{

    use DICTrait;
    use SrTileTrait;

    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var self[]
     */
    protected static $instances = [];
    /**
     * @var Tile
     */
    protected $tile;
    /**
     * @var ilObjUser
     */
    protected $user;


    /**
     * Certificates constructor
     *
     * @param ilObjUser $user
     * @param Tile      $tile
     */
    private function __construct(ilObjUser $user, Tile $tile)
    {
        $this->user = $user;
        $this->tile = $tile;
    }


    /**
     * @param ilObjUser $user
     * @param Tile      $tile
     *
     * @return self
     */
    public static function getInstance(ilObjUser $user, Tile $tile) : self
    {
        if (!isset(self::$instances[$user->getId() . "_" . $tile->getTileId()])) {
            self::$instances[$user->getId() . "_" . $tile->getTileId()] = new self($user, $tile);
        }

        return self::$instances[$user->getId() . "_" . $tile->getTileId()];
    }


    /**
     * @return bool
     */
    public function enabled() : bool
    {
        return ($this->enabled_core() || $this->enabled_plugin());
    }


    /**
     * @return bool
     */
    public function enabled_core() : bool
    {
        return self::dic()->certificateActiveValidator()->validate();
    }


    /**
     * @return bool
     */
    public function enabled_plugin() : bool
    {
        return file_exists(__DIR__ . "/../../../Certificate/vendor/autoload.php") && ilCertificatePlugin::getInstance()->isActive();
    }


    /**
     * @return string|null
     */
    public function getCertificateDownloadLink()/*: ?string*/
    {
        $tile = $this->tile->_getSelfOrFirstChildIfShouldDirect();

        switch ($tile->_getIlObject()->getType()) {
            case "crs":
                // First check Certificate plugin
                if ($this->enabled_plugin()) {
                    /**
                     * @var srCertificateDefinition|null $cert_def
                     * @var srCertificate|null           $cert
                     */

                    // An object links to a certificate definition
                    $cert_def = srCertificateDefinition::where(["ref_id" => $tile->getObjRefId()])->first();

                    if ($cert_def !== null) {

                        // Check allow to download certificate
                        if (boolval($cert_def->getDownloadable())) {

                            // A certificate definition links to the certificate of the user
                            $cert = srCertificate::where([
                                "user_id"       => $this->user->getId(),
                                "definition_id" => $cert_def->getId(),
                                "active"        => 1
                            ])->first();

                            if ($cert !== null) {

                                // The certificate must be active and be generated
                                if ($cert->getActive() && intval($cert->getStatus()) === srCertificate::STATUS_PROCESSED) {

                                    self::dic()->ctrl()->setParameterByClass(srCertificateUserGUI::class, "cert_id", $cert->getId());

                                    return self::dic()->ctrl()->getLinkTargetByClass([
                                        ilUIPluginRouterGUI::class,
                                        srCertificateUserGUI::class
                                    ], srCertificateUserGUI::CMD_DOWNLOAD_CERTIFICATE);
                                } else {
                                    // The current user has no activated and generated certificate
                                    return "";
                                }
                            } else {
                                // The current user has no certificate
                                return "";
                            }
                        } else {
                            // Download of certificate disabled for this object
                            return "";
                        }
                    } else {
                        // The Certificate Plugin is not enabled for this object - Use ILIAS core certificate as possible fallback
                        //return "";
                    }
                }

                //@see Modules/Course/classes/class.ilObjCourseGUI.php:3214
                if ($this->enabled_core()) {
                    if (ilCourseParticipants::getDateTimeOfPassed($tile->_getIlObject()->getId(), $this->user->getId())) {
                        self::dic()->ctrl()->setParameterByClass(ilObjCourseGUI::class, ilSrTileUIHookGUI::GET_PARAM_REF_ID, $tile->getObjRefId());

                        return self::dic()->ctrl()->getLinkTargetByClass([ilRepositoryGUI::class, ilObjCourseGUI::class], 'deliverCertificate');
                    }
                }
                break;

            case "sahs":
                if ($this->enabled_core()) {
                    if (self::srTile()->ilias()->learningProgress($this->user)->getStatus($tile->getObjRefId()) === ilLPStatus::LP_STATUS_COMPLETED_NUM) {
                        //the following way of link generation does not work! the above way is the standard(!:-( ILIAS way of link generation for certificate
                        //$this->ctrl->setParameterByClass(ilSAHSPresentationGUI::class, ilSrTileUIHookGUI::GET_PARAM_REF_ID, $obj_ref_id);
                        //return $this->ctrl->getLinkTargetByClass(ilSAHSPresentationGUI::class,'downloadCertificate');
                        return 'ilias.php?baseClass=' . ilSAHSPresentationGUI::class . '&ref_id=' . $tile->getObjRefId() . '&cmd=downloadCertificate';
                    }
                }
                break;

            case "tst":
                if ($this->enabled_core()) {
                    // TODO Certificates for ILIAS Test
                }
                break;

            default:
                break;
        }

        return null;
    }
}
