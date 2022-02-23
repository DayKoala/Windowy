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

namespace DayKoala\scheduler;

use pocketmine\scheduler\Task;

use pocketmine\player\Player;

use DayKoala\inventory\SimpleWindow;

class WindowWait extends Task{

    protected Player $player;
    protected SimpleWindow $inventory;

    protected int | float $timer = 1.5;

    public function __construct(Player $player, SimpleWindow $inventory){
        $this->inventory = $inventory;
        $this->player = $player;
    }

    public function getInventory() : SimpleWindow{
        return $this->inventory;
    }

    public function getPlayer() : Player{
        return $this->player;
    }

    public function onRun() : Void{
        if($this->timer === 0.5){
           $this->inventory->onCreate($this->player);
        }elseif($this->timer == 0){
           $this->player->setCurrentWindow($this->inventory);
        }
        $this->timer -= 0.5;
    }

}