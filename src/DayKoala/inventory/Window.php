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

use pocketmine\player\Player;

use DayKoala\inventory\holder\PlayerHolder;

use DayKoala\inventory\action\WindowTransactionTrait;

use DayKoala\block\BlockEntityMetadata;

class Window extends SimpleInventory implements PlayerHolder{

    use WindowTrait, WindowTransactionTrait;

    protected $closed = true;

    public function __construct(Int $network, Int $size, BlockEntityMetadata $metadata){
        $this->size = $size;
        parent::__construct($size);

        $this->network = $network;
        $this->metadata = $metadata;
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
               $this->size == 54 ? $this->position->add(1, 0, 0) : null
           );
           foreach($packets as $packet):
              $who->getNetworkSession()->sendDataPacket($packet);
           endforeach;
        }
        $this->closed = true;
    }

    public function isClosed() : Bool{
        return $this->closed;
    }

}