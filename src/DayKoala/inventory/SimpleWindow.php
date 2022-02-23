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

use pocketmine\player\Player;

use DayKoala\inventory\holder\PlayerHolder;

use DayKoala\inventory\WindowTrait;

use DayKoala\block\BlockEntityMetadata;

use DayKoala\inventory\tile\DoubleChestWindow;

class SimpleWindow extends SimpleInventory implements InventoryHolder, PlayerHolder{

    use WindowTrait;

    protected bool $closed = true;

    public function __construct(Int $network, Int $size, BlockEntityMetadata $metadata){
        $this->size = $size;
        parent::__construct($size);

        $this->network = $network;
        $this->metadata = $metadata;
    }

    public function open() : Void{
        if($this->holder instanceof Player) $this->holder->setCurrentWindow($this);
    }

    public function close() : Void{
        if($this->holder instanceof Player) $this->holder->removeCurrentWindow();
    }

    public function onCreate(Player $who) : Bool{
        $pos = $who->getPosition();

        $pos->x = $pos->getFloorX();
        $pos->y = $pos->getFloorY() + 3;
        $pos->z = $pos->getFloorZ();

        $this->position = $pos;

        $pair = null;
        if($this instanceof DoubleChestWindow){
           $this->setPair($pair = (clone $pos)->add(1, 0, 0));
        }

        $packets = $this->metadata->create($pos, $pair, $this->name);

        foreach($packets as $packet) $who->getNetworkSession()->sendDataPacket($packet);

        return true;
    }

    public function onRemove(Player $who) : Bool{
        if($this->position){
           
           $pair = null;
           if($this instanceof DoubleChestWindow){
              $pair = $this->getPair();
           }

           $packets = $this->metadata->remove($this->position, $pair);

           foreach($packets as $packet) $who->getNetworkSession()->sendDataPacket($packet);

        }
        $this->position = null;
        $this->holder = null;
        return true;
    }

    public function onOpen(Player $who) : Void{
        $this->holder = $who;

        parent::onOpen($who);

        $this->closed = false;
    }

    public function onClose(Player $who) : Void{
        parent::onClose($who);

        $this->closed = true;
    }

    public function isClosed() : Bool{
        return $this->closed;
    }

    public function getInventory() : self{
        return $this;
    }

}