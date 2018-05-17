<?php
/**
 * Created by PhpStorm.
 * User: yexee
 * Date: 04.05.2018
 * Time: 15:28
 */

namespace pocketmine\level\light;


class SkyLightUpdate extends LightUpdate
{
    public function getLight(int $x, int $y, int $z) : int{
        return $this->level->getBlockSkyLightAt($x & 0x0f, $y & 0x0f, $z & 0x0f);
    }

    public function setLight(int $x, int $y, int $z, int $level){
        $this->level->setBlockSkyLightAt($x & 0x0f, $y & 0x0f, $z & 0x0f, $level);
    }
}