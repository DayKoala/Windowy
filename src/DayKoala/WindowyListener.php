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

use pocketmine\event\Listener;

use pocketmine\event\inventory\InventoryTransactionEvent;

use pocketmine\inventory\transaction\action\SlotChangeAction;

use DayKoala\inventory\Window;

use DayKoala\inventory\action\WindowTransaction;

class WindowyListener implements Listener{

    public function onTransaction(InventoryTransactionEvent $event){
        $window = null;
        foreach($event->getTransaction()->getActions() as $action){
           if($action instanceof SlotChangeAction and $action->getInventory() instanceof Window):
              $window = $action->getInventory();
              break;
           endif;
        }
        if($window and $window->hasTransaction()):
           $action = !$window->processTransaction(new WindowTransaction($event->getTransaction()->getSource(), $window, $action->getTargetItem(), $action->getSourceItem(), $event->isCancelled()));
           if($action){
              $event->cancel();
           }
        endif;
    }

}