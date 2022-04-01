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

use DayKoala\Windowy;

class WindowWait extends Task{

    protected static $wait = [];

    public static function inWait(Player|Int $player) : Bool{
        if($player instanceof Player){
           $player = $player->getXuid();
        }
        return isset(self::$wait[$player]);
    }

    public static function getWait(Player|Int $player) : ?WindowWait{
        if($player instanceof Player){
           $player = $player->getXuid();
        }
        return self::$wait[$player] ?? null;
    }

    public static function addWait(Player $player, SimpleWindow $inventory, Int $time = 8) : Bool{
        if(isset(self::$wait[$player->getXuid()])){
           return false;
        }
        $current = $player->getCurrentWindow();
        if($current instanceof SimpleWindow){
           $current->onRemove($player);
        }
        if($time < 8 or $time > 30){
           $time = 8;
        }
        Windowy::getTaskScheduler()->scheduleRepeatingTask(self::$wait[$player->getXuid()] = new self($player, $inventory, $time), 2);
        return true;
    }

    public static function cancelWait(Player|Int $player) : Void{
        if($player instanceof Player){
           $player = $player->getXuid();
        }
        if(isset(self::$wait[$player])):
           $task = self::$wait[$player];
           if($task->getHandler()->isCancelled() === false){
              $task->getHandler()->cancel();
           }else{
              unset(self::$wait[$player]);
           }
        endif;
    }

    protected Player $player;
    protected SimpleWindow $inventory;

    protected int $min, $max;

    protected bool $created = false;

    protected function __construct(Player $player, SimpleWindow $inventory, Int $time){
        $this->inventory = $inventory;
        $this->player = $player;
        $this->min = $this->max = $time;
    }

    public function getInventory() : SimpleWindow{
        return $this->inventory;
    }

    public function getPlayer() : Player{
        return $this->player;
    }

    public function getTime() : Int{
        return $this->min;
    }

    public function getMaxTime() : Int{
        return $this->max;
    }

    public function isCreated() : Bool{
        return $this->created;
    }

    public function onRun() : Void{
        if($this->min == floor($this->max / 2)){
           $this->created = $this->inventory->onCreate($this->player);
        }elseif($this->min == 1 and $this->created){
           $this->player->setCurrentWindow($this->inventory);
        }elseif($this->min == 0){
           $this->getHandler()->cancel();
           return;
        }
        $this->min -= 1;
    }

    public function onCancel() : Void{
        if(isset(self::$wait[$this->player->getXuid()])) unset(self::$wait[$this->player->getXuid()]);
    }

}