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

use pocketmine\utils\SingletonTrait;

use pocketmine\world\Position;

use pocketmine\network\mcpe\protocol\ContainerOpenPacket;

use pocketmine\network\mcpe\protocol\types\BlockPosition;

use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;

use pocketmine\block\tile\Chest;
use pocketmine\block\tile\NormalFurnace;
use pocketmine\block\tile\Hopper;

use pocketmine\block\BlockLegacyIds;

use pocketmine\player\Player;

use DayKoala\block\BlockEntityMetadata;

use DayKoala\inventory\tile\FurnaceWindow;
use DayKoala\inventory\tile\DoubleChestWindow;

final class WindowFactory{

    use SingletonTrait;

    public static function toWindowPosition(Position $pos) : Position{
        $pos->x = $pos->getFloorX();
        $pos->y = $pos->getFloorY() + 3;
        $pos->z = $pos->getFloorZ();
        return $pos;
    }

    public static function writeContainer(Player $player) : Bool{
        $manager = $player->getNetworkSession()->getInvManager();
        if($manager === null){
           return false;
        }
        $closure = function(Int $id, Window $inventory) use ($player) : Array{
            $pos = self::toWindowPosition($player->getPosition());

            if($inventory instanceof DoubleChestWindow):
               $inventory->setPair($pair = $pos->add(1, 0, 0));
            else:
               $pair = null;
            endif;
            
            $inventory->setPosition($pos);
            foreach($inventory->getMetadata()->create($pos, $pair, $inventory->hasName() ? $inventory->getName() : null) as $packet){
               $player->getNetworkSession()->sendDataPacket($packet, true);
            }
            
            return [ContainerOpenPacket::blockInv($id, $inventory->getNetworkType(), BlockPosition::fromVector3($pair ?? $pos))];
        };
        $callback = $manager->getContainerOpenCallbacks();

        if($callback->contains($closure) === false) $callback->add($closure);

        return true;
    }

    public const CHEST = "Chest";
    public const DOUBLE_CHEST = "Double_Chest";
    public const HOPPER = "Hopper";
    public const FURNACE = "Furnace";

    private $windows = [];

    public function __construct(){
        $this->register(self::CHEST, new Window(WindowTypes::CONTAINER, 27, new BlockEntityMetadata(Chest::class, BlockLegacyIds::CHEST)));
        $this->register(self::DOUBLE_CHEST, new DoubleChestWindow(WindowTypes::CONTAINER, 54, new BlockEntityMetadata(Chest::class, BlockLegacyIds::CHEST)));
        $this->register(self::HOPPER, new Window(WindowTypes::HOPPER, 5, new BlockEntityMetadata(Hopper::class, BlockLegacyIds::HOPPER_BLOCK)));
        $this->register(self::FURNACE, new FurnaceWindow(WindowTypes::FURNACE, 3, new BlockEntityMetadata(NormalFurnace::class, BlockLegacyIds::FURNACE)));
    }

    public function exists(String $id) : Bool{
        return isset($this->windows[$id]);
    }

    public function get(String $id, ?String $name = null) : ?Window{
        if(isset($this->windows[$id]) === false){
           return null;
        }
        $window = clone $this->windows[$id];

        if(is_string($name)) $window->setName($name);
        
        return $window;
    }

    public function register(String $id, Window $inventory, Bool $override = false) : Void{
        if(isset($this->windows[$id]) and $override === false){
           return;
        }
        $this->windows[$id] = $inventory;
    }

    public function unregister(String $id) : Void{
        if(isset($this->windows[$id])) unset($this->windows[$id]);
    }

}