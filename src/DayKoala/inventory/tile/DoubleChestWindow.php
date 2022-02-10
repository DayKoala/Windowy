<?php

namespace DayKoala\inventory\tile;

use pocketmine\math\Vector3;

use pocketmine\player\Player;

use DayKoala\inventory\Window;

class DoubleChestWindow extends Window{

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