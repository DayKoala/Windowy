<?php

namespace DayKoala\inventory;

use pocketmine\utils\SingletonTrait;

use pocketmine\block\tile\Chest;
use pocketmine\block\tile\NormalFurnace;

use pocketmine\block\BlockLegacyIds;

use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;

use pocketmine\player\Player;

class WindowFactory{

    use SingletonTrait;

    public const CHEST = "Chest";
    public const DOUBLE_CHEST = "Double_Chest";

    public const FURNACE = "Furnace";

    protected $windows = [];

    public function __construct(){
        $this->register(self::CHEST, new Window(Chest::class, BlockLegacyIds::CHEST, 27, WindowTypes::CONTAINER));
        $this->register(self::DOUBLE_CHEST, new Window(Chest::class, BlockLegacyIds::CHEST, 54, WIndowTypes::CONTAINER));
        #$this->register(self::FURNACE, new WindowFurnace(NormalFurnace::class, BlockLegacyIds::FURNACE, 3, WindowTypes::FURNACE));
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
        if(isset($this->windows[$id]) === false){
           return null;
        }
        $window = clone $this->windows[$id];
        $window->setHolder($player);
        $window->setName($name);
        return $window;
    }

}