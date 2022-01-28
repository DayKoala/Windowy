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

namespace DayKoala\inventory\action;

use pocketmine\player\Player;

use pocketmine\item\Item;

use pocketmine\inventory\transaction\action\SlotChangeAction;

use pocketmine\event\inventory\InventoryTransactionEvent;

use DayKoala\inventory\Window;

class WindowTransaction{

    public static function sendAction(Window $inventory,  SlotChangeAction $action, InventoryTransactionEvent $event) : Bool{
        $transaction = $inventory->getTransaction() ?? null;
        return $transaction ? $transaction($inventory, $action->getSlot(), $action->getTargetItem(), $action->getSourceItem(), $event) : false;
    }

    protected Window $inventory;
    protected Player $player;

    protected int $slot;

    protected Item $target;
    protected Item $source;

    protected InventoryTransactionEvent $event;

    protected function __construct(Window $window, Int $slot, Item $target, Item $source, InventoryTransactionEvent $event){
        $this->inventory = $window;
        $this->player = $window->getHolder();

        $this->target = $target;
        $this->source = $source;

        $this->slot = $slot;

        $this->event = $event;
    }

    public function getPlayer() : Player{
        return $this->player;
    }

    public function getInventory() : Window{
        return $this->inventory;
    }

    public function getSlot() : Int{
        return $this->slot;
    }

    public function getTargetItem() : Item{
        return $this->target;
    }

    public function getSourceItem() : Item{
        return $this->source;
    }

    public function isCancelled() : Bool{
        return $this->event->isCancelled();
    }

    public function cancel() : Void{
        $this->event->cancel();
    }

    public function uncancel() : Void{
        $this->event->uncancel();
    }

}