<?php
/**
 * Created by PhpStorm.
 * User: yexee
 * Date: 02.05.2018
 * Time: 23:07
 */

namespace pocketmine\level\sound;


use pocketmine\math\Vector3;
use pocketmine\network\protocol\LevelEventPacket;

class ButtonClickSound extends GenericSound
{
    /**
     * ButtonClickSound constructor.
     *
     * @param Vector3 $pos
     */
    public function __construct(Vector3 $pos){
        parent::__construct($pos, LevelEventPacket::EVENT_REDSTONE_TRIGGER);
    }
}