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
 *  @social https://twitter.com/DayKoala
 * 
 */

namespace DayKoala\inventory;

use pocketmine\inventory\SimpleInventory;

use pocketmine\player\Player;

use DayKoala\inventory\holder\PlayerHolder;

use DayKoala\inventory\WindowTrait;

use DayKoala\block\BlockEntityMetadata;

use DayKoala\world\WindowPosition;

class SimpleWindow extends SimpleInventory{

    use PlayerHolder, WindowTrait;

    protected bool $closed = true;

    final public function __construct(Int $network, Int $size, BlockEntityMetadata $metadata){
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
        $this->position = $pos = (new WindowPosition($who->getPosition()))->wadd(0, 3);

        foreach($this->metadata->create($pos, null, $this->name) as $packet){
           $who->getNetworkSession()->sendDataPacket($packet);
        }

        return true;
    }

    public function onRemove(Player $who) : Bool{
        if($this->position){
           foreach($this->metadata->remove($this->position) as $packet){
              $who->getNetworkSession()->sendDataPacket($packet);
           }
           $this->position = null;
        }
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

    public function getClonedInventory() : static{
        $window = new static($this->network, $this->size, $this->metadata);
        if(($contents = $this->getContents()) !== null){
           $window->setContents($contents);
        }
        return $window;
    }

    public function isClosed() : Bool{
        return $this->closed;
    }

}