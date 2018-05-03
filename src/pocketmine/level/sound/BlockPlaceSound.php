<?php

/*
 *
 *  _____   _____   __   _   _   _____  __    __  _____
 * /  ___| | ____| |  \ | | | | /  ___/ \ \  / / /  ___/
 * | |     | |__   |   \| | | | | |___   \ \/ /  | |___
 * | |  _  |  __|  | |\   | | | \___  \   \  /   \___  \
 * | |_| | | |___  | | \  | | |  ___| |   / /     ___| |
 * \_____/ |_____| |_|  \_| |_| /_____/  /_/     /_____/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author iTX Technologies
 * @link https://itxtech.org
 *
 */

namespace pocketmine\level\sound;

use pocketmine\block\Block;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

class BlockPlaceSound extends GenericSound {

	protected $data;

	/**
	 * BlockPlaceSound constructor.
	 *
	 * @param Block $block
	 */
	public function __construct(Block $block){
		parent::__construct($block, LevelSoundEventPacket::SOUND_PLACE, 1);
		$this->data = $block->getId();
	}

    /**
     * @return LevelSoundEventPacket
     */
	public function encode(){
        $pk = new LevelSoundEventPacket;
        $pk->sound = $this->id;
        $pk->pitch = 1;
        $pk->extraData = $this->data;
        list($pk->x, $pk->y, $pk->z) = [$this->x, $this->y, $this->z];

        return $pk;
	}
}