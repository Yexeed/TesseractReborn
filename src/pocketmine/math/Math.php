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
 * Math related classes, like matrices, bounding boxes and vector
 */

namespace pocketmine\math;


abstract class Math {

	/**
	 * @param $n
	 *
	 * @return int
	 */
	public static function floorFloat($n){
		$i = (int) $n;

		return $n >= $i ? $i : $i - 1;
	}

	/**
	 * @param $n
	 *
	 * @return int
	 */
	public static function ceilFloat($n){
		$i = (int) ($n + 1);

		return $n >= $i ? $i : $i - 1;
	}

	/**
	 * @param $value
	 * @param $low
	 * @param $high
	 *
	 * @return mixed
	 */
	public static function clamp($value, $low, $high){
		return min($high, max($low, $value));
	}

    /**
     * Solves a quadratic equation with the given coefficients and returns an array of up to two solutions.
     *
     * @param float $a
     * @param float $b
     * @param float $c
     *
     * @return float[]
     */
    public static function solveQuadratic(float $a, float $b, float $c) : array{
        $discriminant = $b ** 2 - 4 * $a * $c;
        if($discriminant > 0){ //2 real roots
            $sqrtDiscriminant = sqrt($discriminant);
            return [
                (-$b + $sqrtDiscriminant) / (2 * $a),
                (-$b - $sqrtDiscriminant) / (2 * $a)
            ];
        }elseif($discriminant == 0){ //1 real root
            return [
                -$b / (2 * $a)
            ];
        }else{ //No real roots
            return [];
        }
    }
}