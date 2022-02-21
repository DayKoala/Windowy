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

use pocketmine\inventory\transaction\action\SlotChangeAction;

use pocketmine\event\inventory\InventoryTransactionEvent;

use pocketmine\item\Item;

use DayKoala\inventory\SimpleWindow;

class WindowTransaction extends WindowAction{

    public const INVENTORY_ACTION = 0;

    public const TARGET_ITEM_ACTION = 1;
    public const SOURCE_ITEM_ACTION = 2;

    protected Item $target;
    protected Item $source;

    protected int $type;

    private InventoryTransactionEvent $event;

    public function __construct(SimpleWindow $inventory, Player $player, SlotChangeAction $action, InventoryTransactionEvent $event, Int $type = self::INVENTORY_ACTION){
        parent::__construct($inventory, $player);

        $this->slot = $action->getSlot();

        $this->target = $action->getTargetItem();
        $this->source = $action->getSourceItem();

        $this->type = $type;

        $this->event = $event;
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

    public function getType() : Int{
        return $this->type;
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