<?php

namespace DayKoala\inventory\utils;

use pocketmine\item\Item;

class ItemSerializer
{

    public static function serialize(Item $item): string
    {
        // TODO: maybe use enchantment to differentiate
        $data = [
            $item->getName(),
            $item->getId(),
            $item->getMeta(),
            $item->getCount(),
            implode($item->getLore())
        ];
        return hash("md5", implode($data));
    }

    public static function equals(string $hash, Item $item): bool
    {
        return self::serialize($item) === $hash;
    }

}