<?php

namespace pocketmine\item;


class Elytra extends Armor {

	/**
	 * Elytra constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::ELYTRA, $meta, $count, "Elytra Wings");
	}

	/**
	 * @return int
	 */
	public function getMaxStackSize() : int{
		return 1;
	}

	/**
	 * @return int
	 */
	public function getArmorType(){
		return Armor::TYPE_CHESTPLATE;
	}

	/**
	 * @return int
	 */
	public function getMaxDurability(){
		return 431;
	}

	/**
	 * @return bool
	 */
	public function isChestplate(){
		return true;
	}
}
