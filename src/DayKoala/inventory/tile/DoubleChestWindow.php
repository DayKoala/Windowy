<?php

namespace DayKoala\inventory\tile;

use pocketmine\math\Vector3;

use pocketmine\player\Player;

class DoubleChestWindow extends CustomWindow{

    protected $pair = null;

    public function hasPair() : Bool{
        return (Bool) $this->pair;
    }

    public function getPair() : ?Vector3{
        return $this->pair;
    }

    public function setPair(Vector3 $pos) : self{
        $this->pair = $pos;
        return $this;
    }

}