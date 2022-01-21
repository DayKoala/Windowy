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

use pocketmine\network\mcpe\protocol\ContainerSetDataPacket;

class FurnaceWindow extends Window{

    public function setSmeltTime(Int $time) : Void{
        if($this->closed === false):
           $this->holder->syncData($this, ContainerSetDataPacket::PROPERTY_FURNACE_SMELT_PROGRESS, $time);
        endif;
    }

    public function setMaxFuelTime(Int $time) : Void{
        if($this->closed === false):
           $this->holder->syncData($this, ContainerSetDataPacket::PROPERTY_FURNACE_MAX_FUEL_TIME, $time);
        endif;
    }

    public function setRemaningFuelTime(Int $time) : Void{
        if($this->closed === false):
           $this->holder->syncData($this, ContainerSetDataPacket::PROPERTY_FURNACE_REMAINING_FUEL_TIME, $time);
        endif;
    }

}