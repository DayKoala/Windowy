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

use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;

use pocketmine\block\tile\Chest;
use pocketmine\block\tile\NormalFurnace;
use pocketmine\block\tile\Hopper;

use pocketmine\block\BlockLegacyIds;

use DayKoala\inventory\utils\WindowUtils;

use DayKoala\inventory\tile\CustomWindow;

use DayKoala\block\BlockEntityMetadata;

use DayKoala\inventory\tile\FurnaceWindow;
use DayKoala\inventory\tile\DoubleChestWindow;

final class WindowFactory{

    private static $instance = null;

    public static function getInstance() : self{
        return self::$instance ?? (self::$instance = new self());
    }

    private array $windows = [];

    private function __construct(){
        WindowUtils::init();

        $this->register(WindowIds::CHEST, new CustomWindow(WindowTypes::CONTAINER, 27, new BlockEntityMetadata(Chest::class, BlockLegacyIds::CHEST)));
        $this->register(WindowIds::DOUBLE_CHEST, new DoubleChestWindow(WindowTypes::CONTAINER, 54, new BlockEntityMetadata(Chest::class, BlockLegacyIds::CHEST)));
        $this->register(WindowIds::HOPPER, new CustomWindow(WindowTypes::HOPPER, 5, new BlockEntityMetadata(Hopper::class, BlockLegacyIds::HOPPER_BLOCK)));
        $this->register(WindowIds::FURNACE, new FurnaceWindow(WindowTypes::FURNACE, 3, new BlockEntityMetadata(NormalFurnace::class, BlockLegacyIds::FURNACE)));
    }

    public function exists(String $id) : Bool{
        return isset($this->windows[$id]);
    }

    public function get(String $id, ?String $name = null, Bool $clone = true) : ?SimpleWindow{
        if(isset($this->windows[$id]) === false){
           return null;
        }

        $window = $this->windows[$id];
        if($clone){
           $window = $window->getClonedInventory();
        }

        if(is_string($name)) $window->setName($name);
        
        return $window;
    }

    public function register(String $id, SimpleWindow $inventory, Bool $override = false) : Void{
        if(isset($this->windows[$id]) and $override === false){
           return;
        }
        $this->windows[$id] = $inventory;
    }

    public function unregister(String $id) : Void{
        if(isset($this->windows[$id])) unset($this->windows[$id]);
    }

}