<?php
/**
 * Created by PhpStorm.
 * User: yexee
 * Date: 04.05.2018
 * Time: 0:53
 */

namespace pocketmine\block;


use pocketmine\block\utils\ColorBlockMetaHelper;
use pocketmine\item\Tool;

class Concrete extends Solid{

    protected $id = self::CONCRETE;

    /**
     * Concrete constructor.
     *
     * @param int $meta
     */
    public function __construct($meta = 0){
        $this->meta = $meta;
    }

    /**
     * @return float
     */
    public function getHardness(){
        return 1.8;
    }

    /**
     * @return int
     */
    public function getToolType(){
        return Tool::TYPE_PICKAXE;
    }

    /**
     * @return string
     */
    public function getName(){
        return ColorBlockMetaHelper::getColorFromMeta($this->meta) . " Concrete";
    }
}