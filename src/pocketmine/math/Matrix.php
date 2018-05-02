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


class Matrix implements \ArrayAccess {
	private $matrix = [];
	private $rows = 0;
	private $columns = 0;

	/**
	 * Matrix constructor.
	 *
	 * @param       $rows
	 * @param       $columns
	 * @param array $set
	 */
	public function __construct($rows, $columns, array $set = []){
		$this->rows = max(1, (int) $rows);
		$this->columns = max(1, (int) $columns);
		$this->set($set);
	}

	/**
	 * @param array $m
	 */
	public function set(array $m){
		for($r = 0; $r < $this->rows; ++$r){
			$this->matrix[$r] = [];
			for($c = 0; $c < $this->columns; ++$c){
				$this->matrix[$r][$c] = isset($m[$r][$c]) ? $m[$r][$c] : 0;
			}
		}
	}

	/**
	 * @param mixed $offset
	 *
	 * @return bool
	 */
	public function offsetExists($offset){
		return isset($this->matrix[(int) $offset]);
	}

	/**
	 * @param mixed $offset
	 *
	 * @return mixed
	 */
	public function offsetGet($offset){
		return $this->matrix[(int) $offset];
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value){
		$this->matrix[(int) $offset] = $value;
	}

	/**
	 * @param mixed $offset
	 */
	public function offsetUnset($offset){
		unset($this->matrix[(int) $offset]);
	}

	/**
	 * @param Matrix $matrix
	 *
	 * @return bool|Matrix
	 */
	public function add(Matrix $matrix){
		if($this->rows !== $matrix->getRows() or $this->columns !== $matrix->getColumns()){
			return false;
		}
		$result = new Matrix($this->rows, $this->columns);
		for($r = 0; $r < $this->rows; ++$r){
			for($c = 0; $c < $this->columns; ++$c){
				$result->setElement($r, $c, $this->matrix[$r][$c] + $matrix->getElement($r, $c));
			}
		}

		return $result;
	}

	/**
	 * @return int|mixed
	 */
	public function getRows(){
		return ($this->rows);
	}

	/**
	 * @return int|mixed
	 */
	public function getColumns(){
		return ($this->columns);
	}

	/**
	 * @param $row
	 * @param $column
	 * @param $value
	 *
	 * @return bool
	 */
	public function setElement($row, $column, $value){
		if($row > $this->rows or $row < 0 or $column > $this->columns or $column < 0){
			return false;
		}
		$this->matrix[(int) $row][(int) $column] = $value;

		return true;
	}

	/**
	 * @param $row
	 * @param $column
	 *
	 * @return bool
	 */
	public function getElement($row, $column){
		if($row > $this->rows or $row < 0 or $column > $this->columns or $column < 0){
			return false;
		}

		return $this->matrix[(int) $row][(int) $column];
	}

	/**
	 * @param Matrix $matrix
	 *
	 * @return bool|Matrix
	 */
	public function substract(Matrix $matrix){
		if($this->rows !== $matrix->getRows() or $this->columns !== $matrix->getColumns()){
			return false;
		}
		$result = clone $this;
		for($r = 0; $r < $this->rows; ++$r){
			for($c = 0; $c < $this->columns; ++$c){
				$result->setElement($r, $c, $this->matrix[$r][$c] - $matrix->getElement($r, $c));
			}
		}

		return $result;
	}

	/**
	 * @param $number
	 *
	 * @return Matrix
	 */
	public function multiplyScalar($number){
		$result = clone $this;
		for($r = 0; $r < $this->rows; ++$r){
			for($c = 0; $c < $this->columns; ++$c){
				$result->setElement($r, $c, $this->matrix[$r][$c] * $number);
			}
		}

		return $result;
	}

	/**
	 * @param $number
	 *
	 * @return Matrix
	 */
	public function divideScalar($number){
		$result = clone $this;
		for($r = 0; $r < $this->rows; ++$r){
			for($c = 0; $c < $this->columns; ++$c){
				$result->setElement($r, $c, $this->matrix[$r][$c] / $number);
			}
		}

		return $result;
	}

	/**
	 * @return Matrix
	 */
	public function transpose(){
		$result = new Matrix($this->columns, $this->rows);
		for($r = 0; $r < $this->rows; ++$r){
			for($c = 0; $c < $this->columns; ++$c){
				$result->setElement($c, $r, $this->matrix[$r][$c]);
			}
		}

		return $result;
	}

	/**
	 * @param Matrix $matrix
	 *
	 * @return bool|Matrix
	 */
	public function product(Matrix $matrix){
		if($this->columns !== $matrix->getRows()){
			return false;
		}
		$c = $matrix->getColumns();
		$result = new Matrix($this->rows, $c);
		for($i = 0; $i < $this->rows; ++$i){
			for($j = 0; $j < $c; ++$j){
				$sum = 0;
				for($k = 0; $k < $this->columns; ++$k){
					$sum += $this->matrix[$i][$k] * $matrix->getElement($k, $j);
				}
				$result->setElement($i, $j, $sum);
			}
		}

		return $result;
	}

	//Naive Matrix product, O(n^3)

	/**
	 * @return bool|int
	 */
	public function determinant(){
		if($this->isSquare() !== true){
			return false;
		}
		switch($this->rows){
			case 1:
				return 0;
			case 2:
				return $this->matrix[0][0] * $this->matrix[1][1] - $this->matrix[0][1] * $this->matrix[1][0];
			case 3:
				return $this->matrix[0][0] * $this->matrix[1][1] * $this->matrix[2][2] + $this->matrix[0][1] * $this->matrix[1][2] * $this->matrix[2][0] + $this->matrix[0][2] * $this->matrix[1][0] * $this->matrix[2][1] - $this->matrix[2][0] * $this->matrix[1][1] * $this->matrix[0][2] - $this->matrix[2][1] * $this->matrix[1][2] * $this->matrix[0][0] - $this->matrix[2][2] * $this->matrix[1][0] * $this->matrix[0][1];
		}

		return false;
	}


	//Computation of the determinant of 2x2 and 3x3 matrices

	/**
	 * @return bool
	 */
	public function isSquare(){
		return $this->rows === $this->columns;
	}

	/**
	 * @return string
	 */
	public function __toString(){
		$s = "";
		for($r = 0; $r < $this->rows; ++$r){
			$s .= implode(",", $this->matrix[$r]) . ";";
		}

		return "Matrix({$this->rows}x{$this->columns};" . substr($s, 0, -1) . ")";
	}

}