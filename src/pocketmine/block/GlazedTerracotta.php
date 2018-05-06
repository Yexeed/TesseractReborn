<?php
/**
 * Created by PhpStorm.
 * User: yexee
 * Date: 04.05.2018
 * Time: 0:57
 */

namespace pocketmine\block;


use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\Player;

class GlazedTerracotta extends Solid{


    public function getHardness() : float{
        return 1.4;
    }

    public function getToolType() : int{
        return Tool::TYPE_PICKAXE;
    }
    public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null)
    {
        if(!$player !== \null){
            $faces = [
                0 => 4,
                1 => 3,
                2 => 5,
                3 => 2
            ];
            $this->meta = $faces[(~($player->getDirection() - 1)) & 0x03];
        }

        $this->getLevel()->setBlock($block, $this, \true, \true);
    }
}