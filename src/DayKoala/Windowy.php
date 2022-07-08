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

namespace DayKoala;

use pocketmine\plugin\PluginBase;

use pocketmine\scheduler\TaskScheduler;

use DayKoala\inventory\SimpleWindow;
use DayKoala\inventory\WindowFactory;

class Windowy extends PluginBase{

    private static $scheduler = null;

    public static function getTaskScheduler() : ?TaskScheduler{
        return self::$scheduler;
    }

    public static function getWindow(String $id, ?String $name = null, Bool $clone = true) : ?SimpleWindow{
        return WindowFactory::getInstance()->get($id, $name, $clone);
    }

    public function onLoad() : Void{
        self::$scheduler = $this->getScheduler();
    }

    public function onEnable() : Void{
        $this->getServer()->getPluginManager()->registerEvents(new WindowListener(), $this);
    }

}