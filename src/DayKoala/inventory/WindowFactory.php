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
 * @author DayKoala
 * @link https://github.com/DayKoala/Windowy
 * 
 */

namespace DayKoala\inventory;

use pocketmine\utils\SingletonTrait;

use pocketmine\network\mcpe\protocol\ContainerOpenPacket;

use pocketmine\network\mcpe\protocol\types\BlockPosition;

use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;

use pocketmine\block\tile\Chest;
use pocketmine\block\tile\NormalFurnace;

use pocketmine\block\BlockLegacyIds;

use pocketmine\player\Player;

class WindowFactory{

    use SingletonTrait;

    protected static function sendContainer(Int $id, Window $inventory) : Array{
        $inventory->initContainer($inventory->getHolder());
        return [ContainerOpenPacket::blockInv($id, $inventory->getType(), BlockPosition::fromVector3($inventory->getPosition()))];
    }

    private static function addContainer(Player $player) : Bool{
        $manager = $player->getNetworkSession()->getInvManager();
        if($manager === null){
           return false;
        }
        $callback = $manager->getContainerOpenCallbacks();
        if($callback->contains($callable = \Closure::fromCallable([self::class, 'sendContainer'])) === false){
           $callback->add($callable);
        }
        return true;
    }

    public const CHEST = "Chest";
    public const DOUBLE_CHEST = "Double_Chest";

    public const FURNACE = "Furnace";

    protected $windows = [];

    public function __construct(){
        $this->register(self::CHEST, new Window(WindowTypes::CONTAINER, 27, Chest::class, BlockLegacyIds::CHEST));
        $this->register(self::DOUBLE_CHEST, new Window(WindowTypes::CONTAINER, 54, Chest::class, BlockLegacyIds::CHEST));
        $this->register(self::FURNACE, new FurnaceWindow(WindowTypes::FURNACE, 3, NormalFurnace::class, BlockLegacyIds::FURNACE));
    }

    public function exists(String $id) : Bool{
        return isset($this->windows[$id]);
    }

    public function register(String $id, Window $inventory, Bool $override = false) : Void{
        if(isset($this->windows[$id]) and $override === false){
           return;
        }
        $this->windows[$id] = $inventory;
    }

    public function get(Player $player, String $id, String $name = "Window") : ?Window{
        if(isset($this->windows[$id]) === false or self::addContainer($player) === false){
           return null;
        }
        $window = clone $this->windows[$id];
        $window->setHolder($player);
        $window->setName($name);
        return $window;
    }

}