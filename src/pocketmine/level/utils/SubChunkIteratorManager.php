<?php
/**
 * Created by PhpStorm.
 * User: yexee
 * Date: 04.05.2018
 * Time: 15:30
 */

namespace pocketmine\level\utils;


use pocketmine\level\ChunkManager;
use pocketmine\level\format\Chunk;
use pocketmine\level\format\EmptySubChunk;
use pocketmine\level\format\SubChunk;

class SubChunkIteratorManager
{
    /** @var ChunkManager */
    public $level;

    /** @var Chunk|null */
    public $currentChunk;
    /** @var SubChunk|null */
    public $currentSubChunk;

    /** @var int */
    protected $currentX;
    /** @var int */
    protected $currentY;
    /** @var int */
    protected $currentZ;
    /** @var bool */
    protected $allocateEmptySubs = true;

    public function __construct(ChunkManager $level, bool $allocateEmptySubs = true){
        $this->level = $level;
        $this->allocateEmptySubs = $allocateEmptySubs;
    }

    public function moveTo(int $x, int $y, int $z) : bool{
        if($this->currentChunk === null or $this->currentX !== ($x >> 4) or $this->currentZ !== ($z >> 4)){
            $this->currentX = $x >> 4;
            $this->currentZ = $z >> 4;
            $this->currentSubChunk = null;

            $this->currentChunk = $this->level->getChunk($this->currentX, $this->currentZ);
            if($this->currentChunk === null){
                return false;
            }
        }

        if($this->currentSubChunk === null or $this->currentY !== ($y >> 4)){
            $this->currentY = $y >> 4;

            $this->currentSubChunk = $this->currentChunk->getSubChunk($y >> 4, $this->allocateEmptySubs);
            if($this->currentSubChunk instanceof EmptySubChunk){
                return false;
            }
        }

        return true;
    }
}