<?php

/*
 *  __          ___           _                     
 *  \ \        / (_)         | |                    
 *   \ \  /\  / / _ _ __   __| | _____      ___   _ 
 *    \ \/  \/ / | | '_ \ / _` |/ _ \ \ /\ / / | | |
 *     \  /\  /  | | | | | (_| | (_) \ V  V /| |_| |
 *      \/  \/   |_|_| |_|\__,_|\___/ \_/\_/  \__, |
 *                                             __/ |
 *                                            |___/ 
 *  @author DayKoala
 *  @link https://github.com/DayKoala/Windowy
 * 
 */

namespace DayKoala\inventory\tile;

use pocketmine\player\Player;

use pocketmine\math\Vector3;

class DoubleChestWindow extends CustomWindow{

    protected $pair = null;

    public function onCreate(Player $who) : Bool{
        $pos = $who->getPosition();

        $pos->x = $pos->getFloorX();
        $pos->y = $pos->getFloorY() + 3;
        $pos->z = $pos->getFloorZ();

        $this->position = $pos;
        $this->pair = $pos->add(1, 0, 0);

        foreach($this->metadata->create($pos, $this->pair, $this->name) as $packet){
           $who->getNetworkSession()->sendDataPacket($packet);
        }

        return true;
    }

    public function onRemove(Player $who) : Bool{
        if($this->position){
           foreach($this->metadata->remove($this->position, $this->pair) as $packet){
              $who->getNetworkSession()->sendDataPacket($packet);
           }
           $this->position = null;
           $this->pair = null;
        }
        $this->holder = null;
        return true;
    }

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