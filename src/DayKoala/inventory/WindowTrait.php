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

use DayKoala\block\BlockEntityMetadata;

use DayKoala\world\WindowPosition;

trait WindowTrait{

    protected int $network;
    protected int $size;

    protected BlockEntityMetadata $metadata;

    protected ?WindowPosition $position = null;

    public ?string $name = null;

    public function getNetworkType() : Int{
        return $this->network;
    }

    public function getDefaultSize() : Int{
        return $this->size;
    }

    public function getMetadata() : BlockEntityMetadata{
        return $this->metadata;
    }

    public function hasPosition() : Bool{
        return (Bool) $this->position;
    }

    public function getPosition() : ?WindowPosition{
        return $this->position;
    }

    public function hasName() : Bool{
        return (Bool) $this->name;
    }

    public function getName() : ?String{
        return $this->name ?? null;
    }

    public function setName(String $name) : self{
        $this->name = $name;
        return $this;
    }

}