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

namespace DayKoala;

use pocketmine\plugin\PluginBase;

use pocketmine\player\Player;

use DayKoala\inventory\SimpleWindow;
use DayKoala\inventory\WindowFactory;

use DayKoala\inventory\utils\WindowUtils;

use DayKoala\scheduler\WindowWait;

class Windowy extends PluginBase{

    private static $instance = null;

    public static function getWindow(String $id, ?String $name = null) : ?SimpleWindow{
        return WindowFactory::getInstance()->get($id, $name);
    }

    protected $wait = [];

    public function onLoad() : Void{
        self::$instance = $this;

        WindowUtils::init();
    }

    public function onEnable() : Void{
        $this->getServer()->getPluginManager()->registerEvents(new WindowListener($this), $this);
    }

    public function inWindowWait(Player $player) : Bool{
        return isset($this->wait[$player->getXuid()]);
    }

    public function addWindowWait(Player $player, SimpleWindow $inventory) : Void{
        if(isset($this->wait[$player->getXuid()])){
           return;
        }
        $current = $player->getCurrentWindow();
        if($current instanceof SimpleWindow){
           $current->onRemove($player);
        }
        $this->wait[$player->getXuid()] = $this->getScheduler()->scheduleRepeatingTask(new WindowWait($player, $inventory), 5);
    }

    public function removeWindowWait(Player $player) : Void{
        if(isset($this->wait[$player->getXuid()])):

           $task = $this->wait[$player->getXuid()];
           $task->cancel();

           unset($this->wait[$player->getXuid()]);
        endif;
    }

}