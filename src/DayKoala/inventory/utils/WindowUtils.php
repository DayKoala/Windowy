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

namespace DayKoala\inventory\utils;

use Closure;

use pocketmine\player\Player;

use pocketmine\network\mcpe\protocol\ContainerOpenPacket;

use pocketmine\network\mcpe\protocol\types\BlockPosition;

use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;

use DayKoala\inventory\SimpleWindow;

use DayKoala\inventory\WindowFactory;

final class WindowUtils{

    private static $callback = null;

    public static function init(){

        self::$callback = function(Int $id, SimpleWindow $inventory){
            return [ContainerOpenPacket::blockInv($id, $inventory->getNetworkType(), BlockPosition::fromVector3($inventory->getPosition()))];
        };

    }

    public static function hasCallback(Player $player) : Bool{
        $inventoryManager = $player->getNetworkSession()->getInvManager();
        return $inventoryManager ? $inventoryManager->getContainerOpenCallbacks()->contains(self::$callback) : false;
    }

    public static function setCallback(Closure $callback) : Void{
        if(self::isCallbackValid($callback)):
           self::$callback = $callback;
        else:
           throw new \Exception("Invalid Window Callback", 1);
        endif;
    }

    public static function addCallback(Player $player) : Void{
        $inventoryManager = $player->getNetworkSession()->getInvManager();
        if($inventoryManager):
           if($inventoryManager->getContainerOpenCallbacks()->contains(self::$callback)){
              return;
           }
           $inventoryManager->getContainerOpenCallbacks()->add(self::$callback);
        endif;
    }

    public static function removeCallback(Player $player) : Void{
        $inventoryManager = $player->getNetworkSession()->getInvManager();
        if($inventoryManager):
           if($inventoryManager->getContainerOpenCallbacks()->contains(self::$callback) === false){
              return;
           }
           $inventoryManager->getContainerOpenCallbacks()->remove(self::$callback);
        endif;
    }

    public static function isCallbackValid(Closure $callback) : Bool{
        return is_array($callback(WindowTypes::CONTAINER, WindowFactory::getInstance()->get(WindowIds::CHEST)));
    }

    private function __construct(){ /* NOPE */ }

}