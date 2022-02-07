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

use pocketmine\network\mcpe\protocol\ContainerOpenPacket;

use pocketmine\network\mcpe\protocol\types\BlockPosition;

use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;

use pocketmine\block\tile\Chest;
use pocketmine\block\tile\NormalFurnace;
use pocketmine\block\tile\Hopper;

use pocketmine\block\BlockLegacyIds;

use pocketmine\player\Player;

use DayKoala\inventory\tile\FurnaceWindow;

final class WindowFactory{

    use SingletonTrait;

    private static function addContainer(Player $player) : Bool{
        $manager = $player->getNetworkSession()->getInvManager();

        if($manager === null) return false;
        
        $callback = $manager->getContainerOpenCallbacks();

        if($callback->contains($closure = \Closure::fromCallable([self::class, 'sendContainer'])) === false) $callback->add($closure);

        return true;
    }

    private static function sendContainer(Int $id, Window $inventory) : Array{
        return $inventory->initContainer() ? [ContainerOpenPacket::blockInv($id, $inventory->getType(), BlockPosition::fromVector3($inventory->getPosition()))] : [];
    }

    public const CHEST = "Chest";
    public const DOUBLE_CHEST = "Double_Chest";

    public const FURNACE = "Furnace";

    public const HOPPER = "Hopper";

    private $windows = [];

    public function __construct(){
        $this->register(self::CHEST, new Window(WindowTypes::CONTAINER, 27, Chest::class, BlockLegacyIds::CHEST));
        $this->register(self::DOUBLE_CHEST, new Window(WindowTypes::CONTAINER, 54, Chest::class, BlockLegacyIds::CHEST));
        $this->register(self::HOPPER, new Window(WindowTypes::HOPPER, 5, Hopper::class, BlockLegacyIds::HOPPER_BLOCK));
        $this->register(self::FURNACE, new FurnaceWindow(WindowTypes::FURNACE, 3, NormalFurnace::class, BlockLegacyIds::FURNACE));
    }

    public function getWindow(Player $player, String $id, String $name = "Window") : ?Window{
        if(isset($this->windows[$id]) === false or self::addContainer($player) === false){
           return null;
        }
        $window = clone $this->windows[$id];

        $window->setHolder($player);
        $window->setName($name);

        return $window;
    }

    public function exists(String $id) : Bool{
        return isset($this->windows[$id]);
    }

    public function get(String $id) : ?Window{
        return $this->windows[$id] ?? null;
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