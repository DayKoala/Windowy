<?php

namespace DayKoala\inventory;

use pocketmine\inventory\SimpleInventory;

use pocketmine\player\Player;

use pocketmine\world\Position;

class Window extends SimpleInventory{

    use WindowTrait;

    private $type;

    protected $holder;
    protected $position;

    protected $name;

    public function __construct(String $tile, Int $id, Int $size, Int $type){
        parent::__construct($size);

        $this->type = $type;

        $this->writeAdditionalIds($tile, $id);
    }

    public function initInventory(Player $player) : Void{

        $pos = $player->getPosition();

        $pos->x = $pos->getFloorX();
        $pos->y = $pos->getFloorY() + 3;
        $pos->z = $pos->getFloorZ();

        $this->position = $pos;

        $this->sendBlockPacket($player, $pos);

        $nbt = $this->newNBT($pos, $this->name);

        if($this->getSize() === 54){
           $this->sendBlockPacket($player, $side = $pos->add(1, 0, 0));

           $compound = $this->newNBT($side, $this->name)

           ->setInt("pairx", $pos->x)
           ->setInt("pairz", $pos->z);

           $this->replace[] = $player->getWorld()->getBlock($side);

           $this->sendActorPacket($player, $side, $compound);

           $nbt

           ->setInt("pairx", $side->x)
           ->setInt("pairz", $side->z);

        }

        $this->replace[] = $player->getWorld()->getBlock($pos);

        $this->sendActorPacket($player, $pos, $nbt);

        parent::onOpen($player);
    }

    public function onClose(Player $player) : Void{
        parent::onClose($player);

        foreach($this->replace as $block) $this->sendBlockPacket($player, $block->getPosition(), $block);

    }

    public function getType() : Int{
        return $this->type;
    }

    public function getHolder() : ?Player{
        return $this->holder;
    }

    public function setHolder(Player $player) : Void{
        $this->addContainerCallback($player);
        $this->holder = $player;
    }

    public function getPosition() : ?Position{
        return $this->position;
    }

    public function getName() : String{
        return $this->name;
    }

    public function setName(String $name) : Void{
        $this->name = $name;
    }

}