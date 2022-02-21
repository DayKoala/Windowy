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

namespace DayKoala\inventory\action;

use pocketmine\player\Player;

use DayKoala\inventory\SimpleWindow;

class WindowAction{

    protected SimpleWindow $inventory;
    protected Player $player;

    public function __construct(SimpleWindow $inventory, Player $player){
        $this->inventory = $inventory;
        $this->player = $player;
    }

    public function getInventory() : SimpleWindow{
        return $this->inventory;
    }

    public function getPlayer() : Player{
        return $this->player;
    }

}