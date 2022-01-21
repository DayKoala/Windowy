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
 * @author DayKoala
 * @link https://github.com/DayKoala/Windowy
 * 
 */

namespace DayKoala\inventory\action;

use pocketmine\player\Player;

use pocketmine\item\Item;

use DayKoala\inventory\Window;

class WindowTransaction{

    protected $player;
    protected $inventory;

    protected $target;
    protected $source;

    protected $cancelled;

    public function __construct(Player $player, Window $window, Item $target, Item $source, Bool $cancelled){
        $this->player = $player;
        $this->inventory = $window;
        $this->target = $target;
        $this->source = $source;
        $this->cancelled = $cancelled;
    }

    public function getPlayer() : Player{
        return $this->player;
    }

    public function getInventory() : Window{
        return $this->inventory;
    }

    public function getTargetItem() : Item{
        return $this->target;
    }

    public function getSourceItem() : Item{
        return $this->source;
    }

    public function isCancelled() : Bool{
        return $this->cancelled;
    }

}