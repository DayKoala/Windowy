<?php

namespace DayKoala;

use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;

use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;

use pocketmine\inventory\transaction\action\SlotChangeAction;

use DayKoala\inventory\SimpleWindow;

use DayKoala\inventory\WindowFactory;

use DayKoala\inventory\utils\WindowUtils;

use DayKoala\inventory\action\WindowTransaction;

class Windowy extends PluginBase implements Listener{

    public static function getWindow(String $id, ?String $name = null) : ?SimpleWindow{
        return WindowFactory::getInstance()->get($id, $name);
    }

    public function onLoad() : Void{
        WindowUtils::init();
    }

    public function onEnable() : Void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onOpen(InventoryOpenEvent $event){
        if($event->isCancelled()){
           return;
        }
        $inventory = $event->getInventory();
        if(!$inventory instanceof SimpleWindow){
           return;
        }
        $player = $event->getPlayer();
        if(WindowUtils::hasCallback($player) === false){
           WindowUtils::addCallback($player);
        }
        $inventory->onLoad($player);
    }

    public function onClose(InventoryCloseEvent $event){
        $inventory = $event->getInventory();
        if(!$inventory instanceof SimpleWindow){
           return;
        }
        $inventory->onUnLoad($event->getPlayer());
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

}