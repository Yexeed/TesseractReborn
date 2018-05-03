<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace pocketmine\level\particle;

use pocketmine\entity\Entity;
use pocketmine\entity\Item as ItemEntity;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;
use pocketmine\utils\UUID;


class FloatingTextParticle extends Particle {
	//TODO: HACK!

	protected $text;
	protected $title;
	protected $entityId;
	protected $invisible = false;

	/**
	 * @param Vector3 $pos
	 * @param int     $text
	 * @param string  $title
	 */
	public function __construct(Vector3 $pos, $text, $title = ""){
		parent::__construct($pos->x, $pos->y, $pos->z);
		$this->text = $text;
		$this->title = $title;
	}

	/**
	 * @return int
	 */
	public function getText(){
		return $this->text;
	}

	/**
	 * @param $text
	 */
	public function setText($text){
		$this->text = $text;
	}

	/**
	 * @return string
	 */
	public function getTitle(){
		return $this->title;
	}

	/**
	 * @param $title
	 */
	public function setTitle($title){
		$this->title = $title;
	}

	/**
	 * @return bool
	 */
	public function isInvisible(){
		return $this->invisible;
	}

	/**
	 * @param bool $value
	 */
	public function setInvisible($value = true){
		$this->invisible = (bool) $value;
	}

	/**
	 * @return array
	 */
	public function encode(){
		$p = [];

		if($this->entityId === null){
			$this->entityId = Entity::$entityCount++;
		}else{
			$pk0 = new RemoveEntityPacket();
			$pk0->eid = $this->entityId;

			$p[] = $pk0;
		}

		if(!$this->invisible){
            $pk = new AddPlayerPacket();
            $pk->uuid = UUID::fromRandom();
            $pk->username = $this->title;
            $pk->eid = $this->entityId;
            $pk->x = $this->x;
            $pk->y = $this->y - 0.50;
            $pk->z = $this->z;
            $pk->item = Item::get(Item::AIR);
            $flags = (
                (1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG) |
                (1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG) |
                (1 << Entity::DATA_FLAG_IMMOBILE)
            );
            $pk->metadata = [
                Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
                Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $this->title . ($this->text !== "" ? "\n" . $this->text : "")],
                Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0],
            ];

			$p[] = $pk;
		}
		return $p;
	}
}
