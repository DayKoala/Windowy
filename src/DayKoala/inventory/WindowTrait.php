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

namespace DayKoala\inventory;

use pocketmine\player\Player;

use pocketmine\world\Position;

use DayKoala\block\BlockEntityMetadata;

trait WindowTrait{

    protected $network;
    protected $size;

    protected $metadata;

    protected $holder = null;
    protected $position = null;
    protected $pair = null;

    protected $name = null;

    public function getNetworkType() : Int{
        return $this->network;
    }

    public function getDefaultSize() : Int{
        return $this->size;
    }

    public function getMetadata() : BlockEntityMetadata{
        return $this->metadata;
    }

    public function getHolder() : ?Player{
        return $this->holder;
    }

    public function setHolder(Player $player) : self{
        $this->holder = $holder;
        return $this;
    }

    public function getPosition() : ?Position{
        return $this->position;
    }

    public function setPosition(Position $pos) : self{
        $this->position = $pos;
        return $this;
    }

    public function hasName() : Bool{
        return (Bool) $this->name;
    }

    public function getName() : String{
        return $this->name ?? "Unknown";
    }

    public function setName(String $name) : self{
        $this->name = $name;
        return $this;
    }

}