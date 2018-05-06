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

/**
 * All Block classes are in here
 */

namespace pocketmine\block;

use pocketmine\entity\Entity;

use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\level\Level;
use pocketmine\level\MovingObjectPosition;
use pocketmine\level\Position;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\metadata\Metadatable;
use pocketmine\metadata\MetadataValue;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class Block extends Position implements BlockIds, Metadatable {

    /**
     * Returns a new Block instance with the specified ID, meta and position.
     *
     * This function redirects to {@link BlockFactory#get}.
     *
     * @param int           $id
     * @param int           $meta
     * @param Position|null $pos
     *
     * @return Block
     */
    public static function get(int $id, int $meta = 0, Position $pos = \null) : Block{
        return BlockFactory::get($id, $meta, $pos);
    }

    /** @var int */
    protected $id;
    /** @var int */
    protected $meta = 0;
    /** @var string|null */
    protected $fallbackName;
    /** @var int|null */
    protected $itemId;

    /** @var AxisAlignedBB */
    protected $boundingBox = \null;

    /**
     * @param int         $id     The block type's ID, 0-255
     * @param int         $meta   Meta value of the block type
     * @param string|null $name   English name of the block type (TODO: implement translations)
     * @param int         $itemId The item ID of the block type, used for block picking and dropping items.
     */
    public function __construct(int $id, int $meta = 0, string $name = null, int $itemId = null){
        $this->id = $id;
        $this->meta = $meta;
        $this->fallbackName = $name;
        $this->itemId = $itemId;
    }

    /**
     * @return bool
     */
    public function isSolid(){
        return true;
    }

    /**
     * @return bool
     */
    public function isTransparent(){
        return false;
    }

    /**
     * @return int
     */
    public function getHardness(){
        return 10;
    }

    /**
     * @return int 0-15
     */
    public function getLightLevel(){
        return 0;
    }

    /**
     * Places the Block, using block space and block target, and side. Returns if the block has been placed.
     *
     * @param Item   $item
     * @param Block  $block
     * @param Block  $target
     * @param int    $face
     * @param float  $fx
     * @param float  $fy
     * @param float  $fz
     * @param Player $player = null
     *
     * @return bool
     */
    public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
        return $this->getLevel()->setBlock($this, $this, true, true);
    }

    /**
     * Returns if the item can be broken with an specific Item
     *
     * @param Item $item
     *
     * @return bool
     */
    public function isBreakable(Item $item){
        return true;
    }

    /**
     * @return int
     */
    public function tickRate() : int{
        return 10;
    }

    /**
     * Do the actions needed so the block is broken with the Item
     *
     * @param Item $item
     *
     * @return mixed
     */
    public function onBreak(Item $item){
        return $this->getLevel()->setBlock($this, new Air(), true, true);
    }

    /**
     * Fires a block update on the Block
     *
     * @param int $type
     *
     * @return void
     */
    public function onUpdate($type){

    }

    /**
     * Do actions when activated by Item. Returns if it has done anything
     *
     * @param Item   $item
     * @param Player $player
     *
     * @return bool
     */
    public function onActivate(Item $item, Player $player = null){
        return false;
    }

    /**
     * @return int
     */
    public function getResistance(){
        return $this->getHardness() * 5;
    }

    /**
     * @return int
     */
    public function getBurnAbility() : int{
        return 0;
    }

    /**
     * @return bool
     */
    public function isTopFacingSurfaceSolid(){
        if($this->isSolid()){
            return true;
        }else{
            if($this instanceof Stair and ($this->getDamage() & 4) == 4){
                return true;
            }elseif($this instanceof Slab and ($this->getDamage() & 8) == 8){
                return true;
            }elseif($this instanceof SnowLayer and ($this->getDamage() & 7) == 7){
                return true;
            }
        }

        return false;
    }

    /**
     * @return int
     */
    final public function getDamage(){
        return $this->meta;
    }

    /**
     * @return bool
     */
    public function canNeighborBurn(){
        for($face = 0; $face < 5; $face++){
            if($this->getSide($face)->getBurnChance() > 0){
                return true;
            }
        }

        return false;
    }

    /**
     * @return int
     */
    public function getBurnChance() : int{
        return 0;
    }

    /**
     * Returns the Block on the side $side, works like Vector3::side()
     *
     * @param int $side
     * @param int $step
     *
     * @return Block
     */
    public function getSide($side, $step = 1){
        if($this->isValid()){
            return $this->getLevel()->getBlock(Vector3::getSide($side, $step));
        }

        return Block::get(Item::AIR, 0, Position::fromObject(Vector3::getSide($side, $step)));
    }

    /**
     * @param int      $id
     * @param int      $meta
     * @param Position $pos
     *
     * @return Block
     */
    public static function getOLD($id, $meta = 0, Position $pos = null){
        //TODO
        if($id > 0xff){
            trigger_error("BlockID cannot be higher than 255, defaulting to 0", E_USER_NOTICE);
            $id = 0;
        }
        try{
            $block = self::$list[$id];
            if($block !== null){
                $block = new $block($meta);
            }else{
                $block = new Block($id, $meta);
            }
        }catch(\RuntimeException $e){
            $block = new Block($id, $meta);
        }

        if($pos !== null){
            $block->x = $pos->x;
            $block->y = $pos->y;
            $block->z = $pos->z;
            $block->level = $pos->level;
        }

        return $block;
    }

    /**
     * @return float
     */
    public function getFrictionFactor(){
        return 0.6;
    }

    /**
     * @return bool
     */
    public function isPlaceable(){
        return $this->canBePlaced();
    }

    /**
     * AKA: Block->isPlaceable
     *
     * @return bool
     */
    public function canBePlaced(){
        return true;
    }

    /**
     * AKA: Block->canBeReplaced()
     *
     * @return bool
     */
    public function canBeReplaced(){
        return false;
    }

    /**
     * AKA: Block->isFlowable
     *
     * @return bool
     */
    public function canBeFlowedInto(){
        return false;
    }

    /**
     * AKA: Block->isActivable
     *
     * @return bool
     */
    public function canBeActivated() : bool{
        return false;
    }

    /**
     * @return bool
     */
    public function activate(){
        return false;
    }

    /**
     * @return bool
     */
    public function deactivate(){
        return false;
    }

    /**
     * @param Block|null $from
     *
     * @return bool
     */
    public function isActivated(Block $from = null){
        return false;
    }

    /**
     * @return bool
     */
    public function hasEntityCollision(){
        return false;
    }

    /**
     * @return bool
     */
    public function canPassThrough(){
        return false;
    }

    /**
     * @param Entity  $entity
     * @param Vector3 $vector
     */
    public function addVelocityToEntity(Entity $entity, Vector3 $vector){

    }

    /**
     * @param int $meta
     */
    final public function setDamage($meta){
        $this->meta = $meta & 0x0f;
    }

    /**
     * Sets the block position to a new Position object
     *
     * @param Position $v
     */
    final public function position(Position $v){
        $this->x = (int) $v->x;
        $this->y = (int) $v->y;
        $this->z = (int) $v->z;
        $this->level = $v->level;
        $this->boundingBox = null;
    }

    /**
     * Returns an array of Item objects to be dropped
     *
     * @param Item $item
     *
     * @return array
     */
    public function getDrops(Item $item) : array{
        if(!BlockFactory::isRegistered($this->getId())){ //Unknown blocks
            return [];
        }else{
            return [
                [$this->getId(), $this->getDamage(), 1],
            ];
        }
    }

    /**
     * @return int
     */
    final public function getId(){
        return $this->id;
    }

    /**
     * Returns the seconds that this block takes to be broken using an specific Item
     *
     * @param Item $item
     *
     * @return float
     */
    public function getBreakTime(Item $item){
        $base = $this->getHardness() * 1.5;
        if($this->canBeBrokenWith($item)){
            if($this->getToolType() === Tool::TYPE_SHEARS and $item->isShears()){
                $base /= 15;
            }elseif(
                ($this->getToolType() === Tool::TYPE_PICKAXE and ($tier = $item->isPickaxe()) !== false) or
                ($this->getToolType() === Tool::TYPE_AXE and ($tier = $item->isAxe()) !== false) or
                ($this->getToolType() === Tool::TYPE_SHOVEL and ($tier = $item->isShovel()) !== false)
            ){
                switch($tier){
                    case Tool::TIER_WOODEN:
                        $base /= 2;
                        break;
                    case Tool::TIER_STONE:
                        $base /= 4;
                        break;
                    case Tool::TIER_IRON:
                        $base /= 6;
                        break;
                    case Tool::TIER_DIAMOND:
                        $base /= 8;
                        break;
                    case Tool::TIER_GOLD:
                        $base /= 12;
                        break;
                }
            }
        }else{
            $base *= 3.33;
        }

        if($item->isSword()){
            $base *= 0.5;
        }

        return $base;
    }

    /**
     * @param Item $item
     *
     * @return bool
     */
    public function canBeBrokenWith(Item $item){
        return $this->getHardness() !== -1;
    }

    /**
     * @return int
     */
    public function getToolType(){
        return Tool::TYPE_NONE;
    }

    /**
     * @return string
     */
    public function __toString(){
        return "Block[" . $this->getName() . "] (" . $this->getId() . ":" . $this->getDamage() . ")";
    }

    public function canClimb() : bool{
        return false;
    }

    /**
     * @return string
     */
    public function getName(){
        return "Unknown";
    }

    /**
     * Checks for collision against an AxisAlignedBB
     *
     * @param AxisAlignedBB $bb
     *
     * @return bool
     */
    public function collidesWithBB(AxisAlignedBB $bb){
        $bb2 = $this->getBoundingBox();

        return $bb2 !== null and $bb->intersectsWith($bb2);
    }

    /**
     * @return AxisAlignedBB
     */
    public function getBoundingBox(){
        if($this->boundingBox === null){
            $this->boundingBox = $this->recalculateBoundingBox();
        }

        return $this->boundingBox;
    }

    /**
     * @return AxisAlignedBB
     */
    protected function recalculateBoundingBox(){
        return new AxisAlignedBB(
            $this->x,
            $this->y,
            $this->z,
            $this->x + 1,
            $this->y + 1,
            $this->z + 1
        );
    }

    /**
     * @param Entity $entity
     */
    public function onEntityCollide(Entity $entity){

    }

    /**
     * @param Vector3 $pos1
     * @param Vector3 $pos2
     *
     * @return MovingObjectPosition
     */
    public function calculateIntercept(Vector3 $pos1, Vector3 $pos2){
        $bb = $this->getBoundingBox();
        if($bb === null){
            return null;
        }

        $v1 = $pos1->getIntermediateWithXValue($pos2, $bb->minX);
        $v2 = $pos1->getIntermediateWithXValue($pos2, $bb->maxX);
        $v3 = $pos1->getIntermediateWithYValue($pos2, $bb->minY);
        $v4 = $pos1->getIntermediateWithYValue($pos2, $bb->maxY);
        $v5 = $pos1->getIntermediateWithZValue($pos2, $bb->minZ);
        $v6 = $pos1->getIntermediateWithZValue($pos2, $bb->maxZ);

        if($v1 !== null and !$bb->isVectorInYZ($v1)){
            $v1 = null;
        }

        if($v2 !== null and !$bb->isVectorInYZ($v2)){
            $v2 = null;
        }

        if($v3 !== null and !$bb->isVectorInXZ($v3)){
            $v3 = null;
        }

        if($v4 !== null and !$bb->isVectorInXZ($v4)){
            $v4 = null;
        }

        if($v5 !== null and !$bb->isVectorInXY($v5)){
            $v5 = null;
        }

        if($v6 !== null and !$bb->isVectorInXY($v6)){
            $v6 = null;
        }

        $vector = $v1;

        if($v2 !== null and ($vector === null or $pos1->distanceSquared($v2) < $pos1->distanceSquared($vector))){
            $vector = $v2;
        }

        if($v3 !== null and ($vector === null or $pos1->distanceSquared($v3) < $pos1->distanceSquared($vector))){
            $vector = $v3;
        }

        if($v4 !== null and ($vector === null or $pos1->distanceSquared($v4) < $pos1->distanceSquared($vector))){
            $vector = $v4;
        }

        if($v5 !== null and ($vector === null or $pos1->distanceSquared($v5) < $pos1->distanceSquared($vector))){
            $vector = $v5;
        }

        if($v6 !== null and ($vector === null or $pos1->distanceSquared($v6) < $pos1->distanceSquared($vector))){
            $vector = $v6;
        }

        if($vector === null){
            return null;
        }

        $f = -1;

        if($vector === $v1){
            $f = 4;
        }elseif($vector === $v2){
            $f = 5;
        }elseif($vector === $v3){
            $f = 0;
        }elseif($vector === $v4){
            $f = 1;
        }elseif($vector === $v5){
            $f = 2;
        }elseif($vector === $v6){
            $f = 3;
        }

        return MovingObjectPosition::fromBlock($this->x, $this->y, $this->z, $f, $vector->add($this->x, $this->y, $this->z));
    }

    /**
     * @param string        $metadataKey
     * @param MetadataValue $metadataValue
     */
    public function setMetadata($metadataKey, MetadataValue $metadataValue){
        if($this->getLevel() instanceof Level){
            $this->getLevel()->getBlockMetadata()->setMetadata($this, $metadataKey, $metadataValue);
        }
    }

    /**
     * @param string $metadataKey
     *
     * @return null|MetadataValue[]|\WeakMap
     */
    public function getMetadata($metadataKey){
        if($this->getLevel() instanceof Level){
            return $this->getLevel()->getBlockMetadata()->getMetadata($this, $metadataKey);
        }

        return null;
    }

    /**
     * @param string $metadataKey
     */
    public function hasMetadata($metadataKey){
        if($this->getLevel() instanceof Level){
            $this->getLevel()->getBlockMetadata()->hasMetadata($this, $metadataKey);
        }
    }

    /**
     * @param string $metadataKey
     * @param Plugin $plugin
     */
    public function removeMetadata($metadataKey, Plugin $plugin){
        if($this->getLevel() instanceof Level){
            $this->getLevel()->getBlockMetadata()->removeMetadata($this, $metadataKey, $plugin);
        }
    }
}
