<?php

/*
 *
 *    _______                                _
 *   |__   __|                              | |
 *      | | ___  ___ ___  ___ _ __ __ _  ___| |_
 *      | |/ _ \/ __/ __|/ _ \  __/ _` |/ __| __|
 *      | |  __/\__ \__ \  __/ | | (_| | (__| |_
 *      |_|\___||___/___/\___|_|  \__,_|\___|\__|
 *
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Tessetact Team
 * @link http://www.github.com/TesseractTeam/Tesseract
 * 
 *
 */


namespace pocketmine\level\generator\populator;

use pocketmine\utils\Random;

abstract class VariableAmountPopulator extends Populator {
	protected $baseAmount;
	protected $randomAmount;
	protected $odd;

	/**
	 * VariableAmountPopulator constructor.
	 *
	 * @param int $baseAmount
	 * @param int $randomAmount
	 * @param int $odd
	 */
	public function __construct(int $baseAmount = 0, int $randomAmount = 0, int $odd = 0){
		$this->baseAmount = $baseAmount;
		$this->randomAmount = $randomAmount;
		$this->odd = $odd;
	}

	/**
	 * @param int $odd
	 */
	public function setOdd(int $odd){
		$this->odd = $odd;
	}

	/**
	 * @param Random $random
	 *
	 * @return bool
	 */
	public function checkOdd(Random $random) : bool{
		if($random->nextRange(0, $this->odd) == 0){
			return true;
		}

		return false;
	}

	/**
	 * @param Random $random
	 *
	 * @return int
	 */
	public function getAmount(Random $random){
		return $this->baseAmount + $random->nextRange(0, $this->randomAmount + 1);
	}

	/**
	 * @return int
	 */
	public function getBaseAmount() : int{
		return $this->baseAmount;
	}

	/**
	 * @param int $baseAmount
	 */
	public final function setBaseAmount(int $baseAmount){
		$this->baseAmount = $baseAmount;
	}

	/**
	 * @return int
	 */
	public function getRandomAmount() : int{
		return $this->randomAmount;
	}

	/**
	 * @param int $randomAmount
	 */
	public final function setRandomAmount(int $randomAmount){
		$this->randomAmount = $randomAmount;
	}
}