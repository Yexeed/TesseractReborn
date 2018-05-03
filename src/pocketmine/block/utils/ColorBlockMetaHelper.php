<?php
/**
 * Created by PhpStorm.
 * User: yexee
 * Date: 04.05.2018
 * Time: 0:52
 */

namespace pocketmine\block\utils;


class ColorBlockMetaHelper
{

    public static function getColorFromMeta(int $meta): string{
        static $names = [
            0 => "White",
            1 => "Orange",
            2 => "Magenta",
            3 => "Light Blue",
            4 => "Yellow",
            5 => "Lime",
            6 => "Pink",
            7 => "Gray",
            8 => "Light Gray",
            9 => "Cyan",
            10 => "Purple",
            11 => "Blue",
            12 => "Brown",
            13 => "Green",
            14 => "Red",
            15 => "Black"
        ];

        return $names[$meta] ?? "Unknown";
    }
}