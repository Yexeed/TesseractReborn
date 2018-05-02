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

namespace pocketmine\event\entity;

use pocketmine\entity\Creeper;
use pocketmine\event\Cancellable;
use pocketmine\entity\Lightning;

class CreeperPowerEvent extends EntityEvent implements Cancellable {
	const CAUSE_SET_ON = 0;
	const CAUSE_SET_OFF = 1;
	const CAUSE_LIGHTNING = 2;
	public static $handlerList = null;
	/** @var  Lightning */
	private $lightning;

	private $cause;

	/**
	 * CreeperPowerEvent constructor.
	 *
	 * @param Creeper        $creeper
	 * @param Lightning|null $lightning
	 * @param int            $cause
	 */
	public function __construct(Creeper $creeper, Lightning $lightning = null, int $cause = self::CAUSE_LIGHTNING){
		$this->entity = $creeper;
		$this->lightning = $lightning;
		$this->cause = $cause;
	}

	/**
	 * @return Lightning
	 */
	public function getLightning(){
		return $this->lightning;
	}

	/**
	 * @return int
	 */
	public function getCause(){
		return $this->cause;
	}

	/**
	 * @return EventName|string
	 */
	public function getName(){
		return "CreeperPowerEvent";
	}

}
