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

namespace pocketmine\math;

use pocketmine\level\MovingObjectPosition;

class AxisAlignedBB {

	public $minX;
	public $minY;
	public $minZ;
	public $maxX;
	public $maxY;
	public $maxZ;

	/**
	 * AxisAlignedBB constructor.
	 *
	 * @param $minX
	 * @param $minY
	 * @param $minZ
	 * @param $maxX
	 * @param $maxY
	 * @param $maxZ
	 */
	public function __construct($minX, $minY, $minZ, $maxX, $maxY, $maxZ){
		$this->minX = $minX;
		$this->minY = $minY;
		$this->minZ = $minZ;
		$this->maxX = $maxX;
		$this->maxY = $maxY;
		$this->maxZ = $maxZ;
	}

	/**
	 * @param $minX
	 * @param $minY
	 * @param $minZ
	 * @param $maxX
	 * @param $maxY
	 * @param $maxZ
	 *
	 * @return $this
	 */
	public function setBounds($minX, $minY, $minZ, $maxX, $maxY, $maxZ){
		$this->minX = $minX;
		$this->minY = $minY;
		$this->minZ = $minZ;
		$this->maxX = $maxX;
		$this->maxY = $maxY;
		$this->maxZ = $maxZ;

		return $this;
	}

	/**
	 * @param $x
	 * @param $y
	 * @param $z
	 *
	 * @return AxisAlignedBB
	 */
	public function addCoord($x, $y, $z){
		$minX = $this->minX;
		$minY = $this->minY;
		$minZ = $this->minZ;
		$maxX = $this->maxX;
		$maxY = $this->maxY;
		$maxZ = $this->maxZ;

		if($x < 0){
			$minX += $x;
		}elseif($x > 0){
			$maxX += $x;
		}

		if($y < 0){
			$minY += $y;
		}elseif($y > 0){
			$maxY += $y;
		}

		if($z < 0){
			$minZ += $z;
		}elseif($z > 0){
			$maxZ += $z;
		}

		return new AxisAlignedBB($minX, $minY, $minZ, $maxX, $maxY, $maxZ);
	}

	/**
	 * @param $x
	 * @param $y
	 * @param $z
	 *
	 * @return AxisAlignedBB
	 */
	public function grow($x, $y, $z){
		return new AxisAlignedBB($this->minX - $x, $this->minY - $y, $this->minZ - $z, $this->maxX + $x, $this->maxY + $y, $this->maxZ + $z);
	}

	/**
	 * @param $x
	 * @param $y
	 * @param $z
	 *
	 * @return $this
	 */
	public function expand($x, $y, $z){
		$this->minX -= $x;
		$this->minY -= $y;
		$this->minZ -= $z;
		$this->maxX += $x;
		$this->maxY += $y;
		$this->maxZ += $z;

		return $this;
	}

	/**
	 * @param $x
	 * @param $y
	 * @param $z
	 *
	 * @return $this
	 */
	public function offset($x, $y, $z){
		$this->minX += $x;
		$this->minY += $y;
		$this->minZ += $z;
		$this->maxX += $x;
		$this->maxY += $y;
		$this->maxZ += $z;

		return $this;
	}

	/**
	 * @param $x
	 * @param $y
	 * @param $z
	 *
	 * @return AxisAlignedBB
	 */
	public function shrink($x, $y, $z){
		return new AxisAlignedBB($this->minX + $x, $this->minY + $y, $this->minZ + $z, $this->maxX - $x, $this->maxY - $y, $this->maxZ - $z);
	}

	/**
	 * @param $x
	 * @param $y
	 * @param $z
	 *
	 * @return $this
	 */
	public function contract($x, $y, $z){
		$this->minX += $x;
		$this->minY += $y;
		$this->minZ += $z;
		$this->maxX -= $x;
		$this->maxY -= $y;
		$this->maxZ -= $z;

		return $this;
	}

	/**
	 * @param AxisAlignedBB $bb
	 *
	 * @return $this
	 */
	public function setBB(AxisAlignedBB $bb){
		$this->minX = $bb->minX;
		$this->minY = $bb->minY;
		$this->minZ = $bb->minZ;
		$this->maxX = $bb->maxX;
		$this->maxY = $bb->maxY;
		$this->maxZ = $bb->maxZ;

		return $this;
	}

	/**
	 * @param $x
	 * @param $y
	 * @param $z
	 *
	 * @return AxisAlignedBB
	 */
	public function getOffsetBoundingBox($x, $y, $z){
		return new AxisAlignedBB($this->minX + $x, $this->minY + $y, $this->minZ + $z, $this->maxX + $x, $this->maxY + $y, $this->maxZ + $z);
	}

	/**
	 * @param AxisAlignedBB $bb
	 * @param               $x
	 *
	 * @return mixed
	 */
	public function calculateXOffset(AxisAlignedBB $bb, $x){
		if($bb->maxY <= $this->minY or $bb->minY >= $this->maxY){
			return $x;
		}
		if($bb->maxZ <= $this->minZ or $bb->minZ >= $this->maxZ){
			return $x;
		}
		if($x > 0 and $bb->maxX <= $this->minX){
			$x1 = $this->minX - $bb->maxX;
			if($x1 < $x){
				$x = $x1;
			}
		}
		if($x < 0 and $bb->minX >= $this->maxX){
			$x2 = $this->maxX - $bb->minX;
			if($x2 > $x){
				$x = $x2;
			}
		}

		return $x;
	}

	/**
	 * @param AxisAlignedBB $bb
	 * @param               $y
	 *
	 * @return mixed
	 */
	public function calculateYOffset(AxisAlignedBB $bb, $y){
		if($bb->maxX <= $this->minX or $bb->minX >= $this->maxX){
			return $y;
		}
		if($bb->maxZ <= $this->minZ or $bb->minZ >= $this->maxZ){
			return $y;
		}
		if($y > 0 and $bb->maxY <= $this->minY){
			$y1 = $this->minY - $bb->maxY;
			if($y1 < $y){
				$y = $y1;
			}
		}
		if($y < 0 and $bb->minY >= $this->maxY){
			$y2 = $this->maxY - $bb->minY;
			if($y2 > $y){
				$y = $y2;
			}
		}

		return $y;
	}

	/**
	 * @param AxisAlignedBB $bb
	 * @param               $z
	 *
	 * @return mixed
	 */
	public function calculateZOffset(AxisAlignedBB $bb, $z){
		if($bb->maxX <= $this->minX or $bb->minX >= $this->maxX){
			return $z;
		}
		if($bb->maxY <= $this->minY or $bb->minY >= $this->maxY){
			return $z;
		}
		if($z > 0 and $bb->maxZ <= $this->minZ){
			$z1 = $this->minZ - $bb->maxZ;
			if($z1 < $z){
				$z = $z1;
			}
		}
		if($z < 0 and $bb->minZ >= $this->maxZ){
			$z2 = $this->maxZ - $bb->minZ;
			if($z2 > $z){
				$z = $z2;
			}
		}

		return $z;
	}

    /**
     * Returns whether any part of the specified AABB is inside (intersects with) this one.
     *
     * @param AxisAlignedBB $bb
     * @param float         $epsilon
     *
     * @return bool
     */
    public function intersectsWith(AxisAlignedBB $bb, float $epsilon = 0.00001) : bool{
        if($bb->maxX - $this->minX > $epsilon and $this->maxX - $bb->minX > $epsilon){
            if($bb->maxY - $this->minY > $epsilon and $this->maxY - $bb->minY > $epsilon){
                return $bb->maxZ - $this->minZ > $epsilon and $this->maxZ - $bb->minZ > $epsilon;
            }
        }
        return false;
    }

	/**
	 * @param Vector3 $vector
	 *
	 * @return bool
	 */
	public function isVectorInside(Vector3 $vector){
		if($vector->x <= $this->minX or $vector->x >= $this->maxX){
			return false;
		}
		if($vector->y <= $this->minY or $vector->y >= $this->maxY){
			return false;
		}

		return $vector->z > $this->minZ and $vector->z < $this->maxZ;
	}

	/**
	 * @return float|int
	 */
	public function getAverageEdgeLength(){
		return ($this->maxX - $this->minX + $this->maxY - $this->minY + $this->maxZ - $this->minZ) / 3;
	}

	/**
	 * @param Vector3 $pos1
	 * @param Vector3 $pos2
	 *
	 * @return MovingObjectPosition
	 */
	public function calculateIntercept(Vector3 $pos1, Vector3 $pos2){
		$v1 = $pos1->getIntermediateWithXValue($pos2, $this->minX);
		$v2 = $pos1->getIntermediateWithXValue($pos2, $this->maxX);
		$v3 = $pos1->getIntermediateWithYValue($pos2, $this->minY);
		$v4 = $pos1->getIntermediateWithYValue($pos2, $this->maxY);
		$v5 = $pos1->getIntermediateWithZValue($pos2, $this->minZ);
		$v6 = $pos1->getIntermediateWithZValue($pos2, $this->maxZ);

		if($v1 !== null and !$this->isVectorInYZ($v1)){
			$v1 = null;
		}

		if($v2 !== null and !$this->isVectorInYZ($v2)){
			$v2 = null;
		}

		if($v3 !== null and !$this->isVectorInXZ($v3)){
			$v3 = null;
		}

		if($v4 !== null and !$this->isVectorInXZ($v4)){
			$v4 = null;
		}

		if($v5 !== null and !$this->isVectorInXY($v5)){
			$v5 = null;
		}

		if($v6 !== null and !$this->isVectorInXY($v6)){
			$v6 = null;
		}

		$vector = null;
        $distance = PHP_INT_MAX;


        foreach([$v1, $v2, $v3, $v4, $v5, $v6] as $v) {
            if ($v !== null and ($d = $pos1->distanceSquared($v)) < $distance) {
                $vector = $v;
                $distance = $d;
            }
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

		return MovingObjectPosition::fromBlock(0, 0, 0, $f, $vector);
	}

	/**
	 * @param Vector3 $vector
	 *
	 * @return bool
	 */
	public function isVectorInYZ(Vector3 $vector){
		return $vector->y >= $this->minY and $vector->y <= $this->maxY and $vector->z >= $this->minZ and $vector->z <= $this->maxZ;
	}

	/**
	 * @param Vector3 $vector
	 *
	 * @return bool
	 */
	public function isVectorInXZ(Vector3 $vector){
		return $vector->x >= $this->minX and $vector->x <= $this->maxX and $vector->z >= $this->minZ and $vector->z <= $this->maxZ;
	}

	/**
	 * @param Vector3 $vector
	 *
	 * @return bool
	 */
	public function isVectorInXY(Vector3 $vector){
		return $vector->x >= $this->minX and $vector->x <= $this->maxX and $vector->y >= $this->minY and $vector->y <= $this->maxY;
	}

	/**
	 * @return string
	 */
	public function __toString(){
		return "AxisAlignedBB({$this->minX}, {$this->minY}, {$this->minZ}, {$this->maxX}, {$this->maxY}, {$this->maxZ})";
	}
}