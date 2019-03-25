<?php

namespace srag\Plugins\SrTile\Recommend;

use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\Notifications4Plugins\Notification\Repository as NotificationRepository;
use srag\Plugins\Notifications4Plugins\Sender\Repository as SenderRepository;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;
use Throwable;

/**
 * Class Recommend
 *
 * @package srag\Plugins\SrTile\Recommend
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Recommend {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	/**
	 * @var Tile
	 */
	protected $tile;
	/**
	 * @var string
	 */
	protected $recommended_to = "";
	/**
	 * @var string
	 */
	protected $message = "";


	/**
	 * Recommend constructor
	 */
	public function __construct(Tile $tile) {
		$this->tile = $tile;
	}


	/**
	 * @return bool
	 */
	public function send(): bool {
		try {
			$mail_template = $this->tile->getRecommendMailTemplate();

			$notification = NotificationRepository::getInstance()->getNotificationByName($mail_template);

			$sender = SenderRepository::getInstance()->factory()->externalMail($this->getRecommendedTo());

			$placeholders = [
				"link" => $this->getLink(),
				"message" => $this->getMessage(),
				"object" => $this->tile->_getIlObject(),
				"user" => self::dic()->user()
			];

			SenderRepository::getInstance()->send($sender, $notification, $placeholders, $placeholders["user"]->getLanguage());

			return true;
		} catch (Throwable $ex) {
			return false;
		}
	}


	/**
	 * @return string
	 */
	public function getLink(): string {
		return $this->tile->_getLink();
	}


	/**
	 * @return string
	 */
	public function getRecommendedTo(): string {
		return $this->recommended_to;
	}


	/**
	 * @param string $recommended_to
	 */
	public function setRecommendedTo(string $recommended_to)/*: void*/ {
		$this->recommended_to = $recommended_to;
	}


	/**
	 * @return string
	 */
	public function getMessage(): string {
		return $this->message;
	}


	/**
	 * @param string $message
	 */
	public function setMessage(string $message)/*: void*/ {
		$this->message = $message;
	}
}
