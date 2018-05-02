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

namespace pocketmine\entity;

use pocketmine\block\Block;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\Timings;
use pocketmine\item\Item as ItemItem;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\network\protocol\EntityEventPacket;
use pocketmine\Player;
use pocketmine\utils\BlockIterator;

abstract class Living extends Entity implements Damageable {

	protected $gravity = 0.08;
	protected $drag = 0.02;

	protected $attackTime = 0;

	protected $invisible = false;

	public function saveNBT(){
		parent::saveNBT();
		$this->namedtag->Health = new ShortTag("Health", $this->getHealth());
	}

	/**
	 * @return mixed
	 */
	public abstract function getName();

	/**
	 * @param Entity $entity
	 *
	 * @return bool
	 */
	public function hasLineOfSight(Entity $entity){
		//TODO: head height
		return true;
		//return $this->getLevel()->rayTraceBlocks(Vector3::createVector($this->x, $this->y + $this->height, $this->z), Vector3::createVector($entity->x, $entity->y + $entity->height, $entity->z)) === null;
	}

	/**
	 * @param float                   $amount
	 * @param EntityRegainHealthEvent $source
	 */
	public function heal($amount, EntityRegainHealthEvent $source){
		parent::heal($amount, $source);
		if($source->isCancelled()){
			return;
		}

		$this->attackTime = 0;
	}

	public function kill(){
		if(!$this->isAlive()){
			return;
		}
		parent::kill();

		$this->server->getPluginManager()->callEvent($ev = new EntityDeathEvent($this, $this->getDrops()));
		foreach($ev->getDrops() as $item){
			$this->getLevel()->dropItem($this, $item);
		}
	}

	/**
	 * @return ItemItem[]
	 */
	public function getDrops(){
		return [];
	}

	/**
	 * @param int $tickDiff
	 *
	 * @return bool
	 */
	public function entityBaseTick($tickDiff = 1){
		Timings::$timerLivingEntityBaseTick->startTiming();

		$hasUpdate = parent::entityBaseTick($tickDiff);

		if($this->isAlive()){
			if($this->isInsideOfSolid()){
				$hasUpdate = true;
				$ev = new EntityDamageEvent($this, EntityDamageEvent::CAUSE_SUFFOCATION, 1);
				$this->attack(1, $ev);
			}

			if(!$this->canBreathe()){
				if($this->isBreathing()){
					$this->setBreathing(false);
				}
				$this->doAirSupplyTick($tickDiff);
			}elseif(!$this->isBreathing()){
				$this->setBreathing(true);
				$this->setAirSupplyTicks($this->getMaxAirSupplyTicks());
			}
		}

		if($this->attackTime > 0){
			$this->attackTime -= $tickDiff;
		}

		Timings::$timerLivingEntityBaseTick->stopTiming();

		return $hasUpdate;
	}

	/**
	 * @param int $tickDiff
	 */
	protected function doAirSupplyTick(int $tickDiff){
		$ticks = $this->getAirSupplyTicks() - $tickDiff;

		if($ticks <= -20){
			$this->setAirSupplyTicks(0);
			$this->onAirExpired();
		}else{
			$this->setAirSupplyTicks($ticks);
		}
	}

	/**
	 * @return bool
	 */
	public function canBreathe() : bool{
		return $this->hasEffect(Effect::WATER_BREATHING) or !$this->isInsideOfWater();
	}

	/**
	 * @return bool
	 */
	public function isBreathing() : bool{
		return $this->getDataFlag(self::DATA_FLAGS, self::DATA_FLAG_BREATHING);
	}

	/**
	 * @param bool $value
	 */
	public function setBreathing(bool $value = true){
		$this->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_BREATHING, $value);
	}

	/**
	 * @return int
	 */
	public function getAirSupplyTicks() : int{
		return $this->getDataProperty(self::DATA_AIR);
	}

	/**
	 * @param int $ticks
	 */
	public function setAirSupplyTicks(int $ticks){
		$this->setDataProperty(self::DATA_AIR, self::DATA_TYPE_SHORT, $ticks);
	}

	/**
	 * @return int
	 */
	public function getMaxAirSupplyTicks() : int{
		return $this->getDataProperty(self::DATA_MAX_AIR);
	}

	/**
	 * @param int $ticks
	 */
	public function setMaxAirSupplyTicks(int $ticks){
		$this->setDataProperty(self::DATA_AIR, self::DATA_TYPE_SHORT, $ticks);
	}

	public function onAirExpired(){
		$ev = new EntityDamageEvent($this, EntityDamageEvent::CAUSE_DROWNING, 2);
		$this->attack(2, $ev);
	}

	/**
	 * @param float             $damage
	 * @param EntityDamageEvent $source
	 */
	public function attack($damage, EntityDamageEvent $source){
		if($this->attackTime > 0 or $this->noDamageTicks > 0){
			$lastCause = $this->getLastDamageCause();
			if($lastCause !== null and $lastCause->getDamage() >= $damage){
				$source->setCancelled();
			}
		}

		parent::attack($damage, $source);

		if($source->isCancelled()){
			return;
		}

		if($source instanceof EntityDamageByEntityEvent){
			$e = $source->getDamager();
			if($source instanceof EntityDamageByChildEntityEvent){
				$e = $source->getChild();
			}

			if($e->isOnFire() > 0 and !($e instanceof Player)){
				$this->setOnFire(2 * $this->server->getDifficulty());
			}

			$deltaX = $this->x - $e->x;
			$deltaZ = $this->z - $e->z;
			$this->knockBack($e, $damage, $deltaX, $deltaZ, $source->getKnockBack());
		}

		$pk = new EntityEventPacket();
		$pk->eid = $this->getId();
		$pk->event = $this->getHealth() <= 0 ? EntityEventPacket::DEATH_ANIMATION : EntityEventPacket::HURT_ANIMATION; //Ouch!
		$this->server->broadcastPacket($this->hasSpawned, $pk);

		$this->attackTime = 10; //0.5 seconds cooldown
	}

	/**
	 * @param Entity $attacker
	 * @param        $damage
	 * @param        $x
	 * @param        $z
	 * @param float  $base
	 */
	public function knockBack(Entity $attacker, $damage, $x, $z, $base = 0.4){
		$f = sqrt($x * $x + $z * $z);
		if($f <= 0){
			return;
		}

		$f = 1 / $f;

		$motion = new Vector3($this->motionX, $this->motionY, $this->motionZ);

		$motion->x /= 2;
		$motion->y /= 2;
		$motion->z /= 2;
		$motion->x += $x * $f * $base;
		$motion->y += $base;
		$motion->z += $z * $f * $base;

		if($motion->y > $base){
			$motion->y = $base;
		}

		$this->setMotion($motion);
	}

	/**
	 * @param int   $maxDistance
	 * @param array $transparent
	 *
	 * @return Block
	 */
	public function getTargetBlock($maxDistance, array $transparent = []){
		try{
			$block = $this->getLineOfSight($maxDistance, 1, $transparent)[0];
			if($block instanceof Block){
				return $block;
			}
		}catch(\ArrayOutOfBoundsException $e){

		}

		return null;
	}

	/**
	 * @param int   $maxDistance
	 * @param int   $maxLength
	 * @param array $transparent
	 *
	 * @return Block[]
	 */
	public function getLineOfSight($maxDistance, $maxLength = 0, array $transparent = []){
		if($maxDistance > 120){
			$maxDistance = 120;
		}

		if(count($transparent) === 0){
			$transparent = null;
		}

		$blocks = [];
		$nextIndex = 0;

		$itr = new BlockIterator($this->level, $this->getPosition(), $this->getDirectionVector(), $this->getEyeHeight(), $maxDistance);

		while($itr->valid()){
			$itr->next();
			$block = $itr->current();
			$blocks[$nextIndex++] = $block;

			if($maxLength !== 0 and count($blocks) > $maxLength){
				array_shift($blocks);
				--$nextIndex;
			}

			$id = $block->getId();

			if($transparent === null){
				if($id !== 0){
					break;
				}
			}else{
				if(!isset($transparent[$id])){
					break;
				}
			}
		}

		return $blocks;
	}

	protected function initEntity(){
		parent::initEntity();

		if(isset($this->namedtag->HealF)){
			$this->namedtag->Health = new FloatTag("Health", (float) $this->namedtag["HealF"]);
			unset($this->namedtag->HealF);
		}elseif(isset($this->namedtag->Health)){
			if(!($this->namedtag->Health instanceof FloatTag)){
				$this->namedtag->Health = new FloatTag("Health", (float) $this->namedtag->Health->getValue());
			}
		}else{
			$this->namedtag->Health = new FloatTag("Health", (float) $this->getMaxHealth());
		}

		$this->setHealth($this->namedtag["Health"]);
	}

	/**
	 * @param int $amount
	 */
	public function setHealth($amount){
		$wasAlive = $this->isAlive();
		parent::setHealth($amount);
		if($this->isAlive() and !$wasAlive){
			$pk = new EntityEventPacket();
			$pk->eid = $this->getId();
			$pk->event = EntityEventPacket::RESPAWN;
			$this->server->broadcastPacket($this->hasSpawned, $pk);
		}
	}
}
