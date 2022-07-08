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
 *  @social https://twitter.com/DayKoala
 * 
 */

namespace DayKoala\inventory\tile;

use pocketmine\player\Player;

use DayKoala\world\WindowPosition;

class DoubleChestWindow extends CustomWindow{

    protected ?WindowPosition $pair = null;

    public function onCreate(Player $who) : Bool{

        $this->position = $pos = (new WindowPosition($who->getPosition()))->wadd(0, 3);
        $this->pair = $pos->wadd(1);

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

    public function getPair() : ?WindowPosition{
        return $this->pair;
    }

}