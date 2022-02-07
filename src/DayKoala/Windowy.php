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

use pocketmine\event\Listener;

use pocketmine\player\Player;

use pocketmine\event\inventory\InventoryTransactionEvent;

use pocketmine\inventory\transaction\action\SlotChangeAction;

use DayKoala\inventory\Window;
use DayKoala\inventory\WindowFactory;

use DayKoala\inventory\action\WindowTransaction;

class Windowy extends PluginBase implements Listener{

    public static function getWindow(Player $player, String $id, String $name = "Window") : ?Window{
        return WindowFactory::getInstance()->getWindow($player, $id, $name);
    }

    public function onEnable() : Void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onTransaction(InventoryTransactionEvent $event){
        $window = null;
        foreach($event->getTransaction()->getActions() as $action){
           if($action instanceof SlotChangeAction):
              $inventory = $action->getInventory();
              if($inventory instanceof Window){
                 $window = $inventory;
                 break;
              }
           endif;
        }
        if($window) WindowTransaction::sendTransaction($window, $action, $event);
    }

}