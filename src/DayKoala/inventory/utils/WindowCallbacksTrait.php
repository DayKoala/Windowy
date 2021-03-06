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

namespace DayKoala\inventory\utils;

use Closure;

use pocketmine\item\Item;

trait WindowCallbacksTrait{

    protected ?Closure $closeCallback = null;

    protected ?array $itemCallback = [];

    protected ?Closure $transaction = null;

    public function hasCloseCallback() : Bool{
        return (Bool) $this->closeCallback;
    }

    public function getCloseCallback() : ?Closure{
        return $this->closeCallback;
    }

    public function setCloseCallback(Closure $callback) : self{
        $this->closeCallback = $callback;
        return $this;
    }

    public function removeCloseCallback() : self{
        $this->closeCallback = null;
        return $this;
    }

    public function hasItemCallbacks() : Bool{
        return (Bool) $this->itemCallback;
    }

    public function getItemCallbacks() : Array{
        return $this->itemCallback ?? [];
    }

    public function hasItemCallback(Item $item) : Bool{
        return isset($this->itemCallback[$item->__toString()]);
    }

    public function getItemCallback(Item $item) : ?Closure{
        return $this->itemCallback[$item->__toString()] ?? null;
    }
    
    public function setItemCallback(Item $item, Closure $callback) : self{
        $this->itemCallback[$item->__toString()] = $callback;
        return $this;
    }

    public function removeItemCallBack(Item $item) : self{
        if(isset($this->itemCallback[$item->__toString()])){
           unset($this->itemCallback[$item->__toString()]);
        }
        return $this;
    }

    public function hasTransaction() : Bool{
        return (Bool) $this->transaction;
    }

    public function getTransaction() : ?Closure{
        return $this->transaction ?? null;
    }

    public function setTransaction(Closure $callback) : self{
        $this->transaction = $callback;
        return $this;
    }

    public function removeTransaction() : self{
        $this->transaction = null;
        return $this;
    }

    public function copyCallbacks(?Closure $transaction = null, ?Closure $closeCallback = null, ?Array $itemCallback = []) : Void{
        $this->transaction = $transaction;
        $this->closeCallback = $closeCallback;
        $this->itemCallback = $itemCallback;
    }

}