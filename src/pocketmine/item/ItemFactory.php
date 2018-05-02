<?php
/**
 * Created by PhpStorm.
 * User: yexee
 * Date: 02.05.2018
 * Time: 19:21
 */

namespace pocketmine\item;


class ItemFactory
{
    public static function get(int $id, int $meta = 0, int $count = 1, string $tags = ""): Item
    {
        return Item::get($id, $meta, $count, $tags);
    }
}