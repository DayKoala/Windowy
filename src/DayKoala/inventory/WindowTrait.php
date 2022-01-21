<?php

namespace DayKoala\inventory;

use pocketmine\block\tile\TileFactory;
use pocketmine\block\tile\Tile;

use pocketmine\block\BlockFactory;

use pocketmine\player\Player;

use pocketmine\math\Vector3;

use pocketmine\block\Block;

use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\network\mcpe\protocol\BlockActorDataPacket;

use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;

use pocketmine\network\mcpe\convert\RuntimeBlockMapping;

use pocketmine\Server;

use pocketmine\nbt\tag\CompoundTag;

use pocketmine\world\Position;

trait WindowTrait{

    protected static function createContainerOpen(Int $id, Window $inventory) : Array{
        $inventory->initInventory($inventory->getHolder());

        return [
            ContainerOpenPacket::blockInv(
                $id, 

                $inventory->getType(), 

                BlockPosition::fromVector3($inventory->getPosition())
            )
        ];
    }

    protected $tile;
    protected $block;

    protected $replace;

    protected function writeAdditionalIds(String $tile, Int $id) : Void{
        $this->tile = TileFactory::getInstance()->getSaveId($tile);
        $this->block = BlockFactory::getInstance()->get($id, 0);
    }

    protected function sendBlockPacket(Player $player, Vector3 $pos, ?Block $block = null) : Void{
        $block = $block ?? $this->block;

        $packet = UpdateBlockPacket::create(

            BlockPosition::fromVector3($pos), 

            RuntimeBlockMapping::getInstance()->toRuntimeId($block->getFullId()), 

            UpdateBlockPacket::FLAG_NETWORK, 
            UpdateBlockPacket::DATA_LAYER_NORMAL

        );
        Server::getInstance()->broadcastPackets([$player], [$packet]);
    }

    protected function sendActorPacket(Player $player, Vector3 $pos, CompoundTag $nbt = null) : Void{
        $nbt = $nbt ?? $this->newNBT($pos);

        $packet = BlockActorDataPacket::create(

            BlockPosition::fromVector3($pos),
            
            new CacheableNbt($nbt)

        );
        Server::getInstance()->broadcastPackets([$player], [$packet]);
    }

    protected function newNBT(Vector3 $pos, String $name = "Window") : CompoundTag{
        $nbt = CompoundTag::create()
        
        ->setString("CustomName", $name)

        ->setString(Tile::TAG_ID, $this->tile)

        ->setInt(Tile::TAG_X, $pos->x)
        ->setInt(Tile::TAG_Y, $pos->y)
        ->setInt(Tile::TAG_Z, $pos->z);

        return $nbt;
    }

    protected function addContainerCallback(Player $player) : Bool{
        $manager = $player->getNetworkSession()->getInvManager();
        if($manager === null){
           return false;
        }
        $callback = $manager->getContainerOpenCallbacks();
        if($callback->contains($callable = \Closure::fromCallable([self::class, 'createContainerOpen'])) === false){
           $callback->add($callable);
        }
        return true;
    }

}