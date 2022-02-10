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

namespace DayKoala\inventory;

use pocketmine\inventory\SimpleInventory;
use pocketmine\inventory\InventoryHolder;

use pocketmine\item\Item;

use pocketmine\player\Player;

use DayKoala\inventory\holder\PlayerHolder;

use DayKoala\inventory\action\WindowTransactionTrait;

use DayKoala\block\BlockEntityMetadata;

use DayKoala\inventory\tile\DoubleChestWindow;

class Window extends SimpleInventory implements PlayerHolder, InventoryHolder{

    use WindowTrait, WindowTransactionTrait;

    protected $closed = true;

    public function __construct(Int $network, Int $size, BlockEntityMetadata $metadata){
        $this->size = $size;
        parent::__construct($size);

        $this->network = $network;
        $this->metadata = $metadata;
    }

    public function getInventory(){
        return $this;
    }

    public function setItem(Int $slot, Item $item, ?\Closure $closure = null) : Void{
        $target = $this->getItem($slot);
        if($target->equalsExact($item) === false){

           if($this->hasItemTransaction($target)) $this->removeItemTransaction($target);

        }
        parent::setItem($slot, $item);

        if($closure) $this->setItemTransaction($item, $closure);
    }

    public function onContentChange(Array $items) : Void{
        foreach($items as $slot => $item):
           if(!$this->hasItemTransaction($item)){
              continue;
           }
           $target = $this->getItem($slot);
           if(!$target->equalsExact($item)){
              continue;
           }
           $this->removeItemTransaction($target);
        endforeach;
    }

    public function onOpen(Player $who) : Void{
        $this->holder = $who;

        parent::onOpen($who);

        $this->closed = false;
    }

    public function onClose(Player $who) : Void{
        parent::onClose($who);

        if($this->position){
           $packets = $this->metadata->remove(
               $this->position,
               $this->size == 54 ? $this?->getPair() : null
           );
           foreach($packets as $packet) $who->getNetworkSession()->sendDataPacket($packet);
        }
        $this->closed = true;
    }

    public function isClosed() : Bool{
        return $this->closed;
    }

}