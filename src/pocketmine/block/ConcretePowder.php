<?php
/**
 * Created by PhpStorm.
 * User: yexee
 * Date: 04.05.2018
 * Time: 0:54
 */

namespace pocketmine\block;


use pocketmine\block\utils\ColorBlockMetaHelper;
use pocketmine\item\Tool;

class ConcretePowder extends Fallable {

    protected $id = self::CONCRETE_POWDER;

    /**
     * ConcretePowder constructor.
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
        return 0.5;
    }

    /**
     * @return float
     */
    public function getResistance(){
        return 2.5;
    }

    /**
     * @return int
     */
    public function getToolType(){
        return Tool::TYPE_SHOVEL;
    }

    /**
     * @return mixed
     */
    public function getName(){
        return ColorBlockMetaHelper::getColorFromMeta($this->meta) . " Concrete Powder";
    }
}