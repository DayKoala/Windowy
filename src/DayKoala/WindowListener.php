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

use pocketmine\event\Listener;

use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;

use pocketmine\event\player\PlayerQuitEvent;

use pocketmine\inventory\transaction\action\SlotChangeAction;

use DayKoala\inventory\SimpleWindow;

use DayKoala\inventory\utils\WindowUtils;

use DayKoala\inventory\action\WindowTransaction;

class WindowListener implements Listener{

    private $plugin;

    public function __construct(Windowy $plugin){
        $this->plugin = $plugin;
    }

    public function onOpen(InventoryOpenEvent $event){
        $inventory = $event->getInventory();
        if(!$inventory instanceof SimpleWindow){
           return;
        }
        $player = $event->getPlayer();
        if($this->plugin->inWindowWait($player, $inventory)):
           if(WindowUtils::hasCallback($player) === false){
              WindowUtils::addCallback($player);
           }
           if($inventory->onCreate($player) === false){
              $event->cancel();  
           }
           $this->plugin->removeWindowWait($player);
        else:
           if($event->isCancelled()){
              return;
           }
           $this->plugin->addWindowWait($player, $inventory);
           $event->cancel();
        endif;
    }

    public function onClose(InventoryCloseEvent $event){
        $inventory = $event->getInventory();
        if($inventory instanceof SimpleWindow) $inventory->onRemove($event->getPlayer());
    }

    public function onTransaction(InventoryTransactionEvent $event){
        foreach($event->getTransaction()->getActions() as $action):

           if(!$action instanceof SlotChangeAction){
              continue;
           }
           $inventory = $action->getInventory();
           if(!$inventory instanceof SimpleWindow){
              continue;
           }
           $target = $action->getTargetItem();
           $source = $action->getSourceItem();
           if($inventory->hasItemCallback($target)){

              $callback = $inventory->getItemCallback($target);
              $type = WindowTransaction::TARGET_ITEM_ACTION;

           }elseif($inventory->hasItemCallback($source)){

              $callback = $inventory->getItemCallback($source);
              $type = WindowTransaction::SOURCE_ITEM_ACTION;

           }else{

              $callback = $inventory->getTransaction();
              $type = WindowTransaction::INVENTORY_ACTION;

           }
           if($callback) $callback(new WindowTransaction($inventory, $event->getTransaction()->getSource(), $action, $event, $type));

         break;

      endforeach;
   }

   public function onQuit(PlayerQuitEvent $event){
       $player = $event->getPlayer();
       if($this->plugin->inWindowWait($player)) $this->plugin->removeWindowWait($player);
   }

}