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

namespace DayKoala\world;

use pocketmine\world\Position;

class WindowPosition extends Position{

    public function __construct(Position $pos){
        parent::__construct($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ(), $pos->world);
    }

    public function wadd(Int $x = 0, Int $y = 0, Int $z = 0) : WindowPosition{
        return new WindowPosition(new Position($this->x + $x, $this->y + $y, $this->z + $z, $this->world));
    }

    public function wreduce(Int $x = 0, Int $y = 0, Int $z = 0) : WindowPosition{
        return new WindowPosition(new Position($this->x - $x, $this->y - $y, $this->z - $z, $this->world));
    }

}