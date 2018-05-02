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

declare(strict_types=1);

namespace pocketmine\level\format;


class EmptySubChunk extends SubChunk {

	/**
	 * EmptySubChunk constructor.
	 */
	public function __construct(){

	}

	/**
	 * @return bool
	 */
	public function isEmpty() : bool{
		return true;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int
	 */
	public function getBlockId(int $x, int $y, int $z) : int{
		return 0;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $id
	 *
	 * @return bool
	 */
	public function setBlockId(int $x, int $y, int $z, int $id) : bool{
		return false;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int
	 */
	public function getBlockData(int $x, int $y, int $z) : int{
		return 0;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $data
	 *
	 * @return bool
	 */
	public function setBlockData(int $x, int $y, int $z, int $data) : bool{
		return false;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int
	 */
	public function getFullBlock(int $x, int $y, int $z) : int{
		return 0;
	}

	/**
	 * @param int  $x
	 * @param int  $y
	 * @param int  $z
	 * @param null $id
	 * @param null $data
	 *
	 * @return bool
	 */
	public function setBlock(int $x, int $y, int $z, $id = null, $data = null) : bool{
		return false;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int
	 */
	public function getBlockLight(int $x, int $y, int $z) : int{
		return 0;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $level
	 *
	 * @return bool
	 */
	public function setBlockLight(int $x, int $y, int $z, int $level) : bool{
		return false;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int
	 */
	public function getBlockSkyLight(int $x, int $y, int $z) : int{
		return 15;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $level
	 *
	 * @return bool
	 */
	public function setBlockSkyLight(int $x, int $y, int $z, int $level) : bool{
		return false;
	}

	/**
	 * @param int $x
	 * @param int $z
	 *
	 * @return string
	 */
	public function getBlockIdColumn(int $x, int $z) : string{
		return "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00";
	}

	/**
	 * @param int $x
	 * @param int $z
	 *
	 * @return string
	 */
	public function getBlockDataColumn(int $x, int $z) : string{
		return "\x00\x00\x00\x00\x00\x00\x00\x00";
	}

	/**
	 * @param int $x
	 * @param int $z
	 *
	 * @return string
	 */
	public function getBlockLightColumn(int $x, int $z) : string{
		return "\x00\x00\x00\x00\x00\x00\x00\x00";
	}

	/**
	 * @param int $x
	 * @param int $z
	 *
	 * @return string
	 */
	public function getSkyLightColumn(int $x, int $z) : string{
		return "\xff\xff\xff\xff\xff\xff\xff\xff";
	}

	/**
	 * @return string
	 */
	public function getBlockIdArray() : string{
		return str_repeat("\x00", 4096);
	}

	/**
	 * @return string
	 */
	public function getBlockDataArray() : string{
		return str_repeat("\x00", 2048);
	}

	/**
	 * @return string
	 */
	public function getBlockLightArray() : string{
		return str_repeat("\x00", 2048);
	}

	/**
	 * @return string
	 */
	public function getSkyLightArray() : string{
		return str_repeat("\xff", 2048);
	}

	/**
	 * @return string
	 */
	public function networkSerialize() : string{
		return "\x00" . str_repeat("\x00", 10240);
	}

	/**
	 * @return string
	 */
	public function fastSerialize() : string{
		throw new \BadMethodCallException("Should not try to serialize empty subchunks");
	}
}