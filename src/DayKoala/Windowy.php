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

namespace DayKoala;

use pocketmine\plugin\PluginBase;

use pocketmine\player\Player;

use DayKoala\inventory\Window;
use DayKoala\inventory\WindowFactory;

class Windowy extends PluginBase{

    public static function getWindow(Player $player, String $id, String $name = "Window") : ?Window{
        return WindowFactory::getInstance()->get($player, $id, $name);
    }

    public function onEnable() : Void{
        $this->getServer()->getPluginManager()->registerEvents(new WindowyListener(), $this);
    }

}