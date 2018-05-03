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

namespace pocketmine\network\mcpe\protocol;

class UnknownPacket extends DataPacket{
	const NETWORK_ID = -1;

	public $payload;

	/**
	 * @return int
	 */
	public function pid(){
		if(strlen($this->payload ?? "") > 0){
			return ord($this->payload{0});
		}
		return self::NETWORK_ID;
	}

	/**
	 * @return string
	 */
	public function getName(){
		return "Unknown Packet";
	}

	public function decode(){
		$this->payload = $this->get(0);
	}

	public function encode(){
		//Do not reset the buffer, this class does not have a valid NETWORK_ID constant.
		$this->put($this->payload);
	}

}