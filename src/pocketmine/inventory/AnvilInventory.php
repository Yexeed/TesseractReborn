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

namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\item\EnchantedBook;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\inventory\AnvilProcessEvent;

class AnvilInventory extends TemporaryInventory {

	const TARGET = 0;
	const SACRIFICE = 1;
	const RESULT = 2;


	/**
	 * AnvilInventory constructor.
	 *
	 * @param Position $pos
	 */
	public function __construct(Position $pos){
		parent::__construct(new FakeBlockMenu($this, $pos), InventoryType::get(InventoryType::ANVIL));
	}

	/**
	 * @param Player $player
	 * @param Item   $resultItem
	 *
	 * @return bool
	 */
	public function onRename(Player $player, Item $resultItem) : bool{
		if(!$resultItem->equals($this->getItem(self::TARGET), true, false, true)){
			//Item does not match target item. Everything must match except the tags.
			return false;
		}

		if($player->getXpLevel() < $resultItem->getRepairCost()){ //Not enough exp
			return false;
		}
		$player->takeXpLevel($resultItem->getRepairCost());

		$this->clearAll();

		return true;
	}

	/**
	 * @param Player $player
	 * @param Item   $target
	 * @param Item   $sacrifice
	 *
	 * @return bool
	 */
	public function process(Player $player, Item $target, Item $sacrifice){
		$resultItem = clone $target;
		Server::getInstance()->getPluginManager()->callEvent($ev = new AnvilProcessEvent($this));
		if($ev->isCancelled()){
			$this->clearAll();

			return false;
		}
		if($sacrifice instanceof EnchantedBook && $sacrifice->hasEnchantments()){ //Enchanted Books!
			foreach($sacrifice->getEnchantments() as $enchant){
				$resultItem->addEnchantment($enchant);
			}

			if($player->getXpLevel() < $resultItem->getRepairCost()){ //Not enough exp
				return false;
			}
			$player->takeXpLevel($resultItem->getRepairCost());

			$this->clearAll();
		}
	}

	/**
	 * @param Transaction $transaction
	 *
	 * @return bool
	 */
	public function processSlotChange(Transaction $transaction) : bool{
		if($transaction->getSlot() === $this->getResultSlotIndex()){
			return false;
		}

		return true;
	}

	/**
	 * @return int
	 */
	public function getResultSlotIndex(){
		return self::RESULT;
	}

	/**
	 * @param int  $index
	 * @param Item $before
	 * @param bool $send
	 */
	public function onSlotChange($index, $before, $send){
		//Do not send anvil slot updates to anyone. This will cause a client crash.
	}

	/**
	 * @param Player $who
	 */
	public function onClose(Player $who){
		parent::onClose($who);

		$this->getHolder()->getLevel()->dropItem($this->getHolder()->add(0.5, 0.5, 0.5), $this->getItem(0));
		$this->getHolder()->getLevel()->dropItem($this->getHolder()->add(0.5, 0.5, 0.5), $this->getItem(1));
		$this->clear(0);
		$this->clear(1);
		$this->clear(2);
	}

	/**
	 * @return FakeBlockMenu|InventoryHolder
	 */
	public function getHolder(){
		return $this->holder;
	}

}
