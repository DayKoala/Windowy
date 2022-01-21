<?php

namespace DayKoala;

use pocketmine\plugin\PluginBase;

class Windowy extends PluginBase{

    public function onEnable() : Void{
        $this->getServer()->getPluginManager()->registerEvents(new WindowyListener($this), $this);
    }

}