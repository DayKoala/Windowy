<?php

namespace DayKoala\inventory\action;

use Closure;

use pocketmine\item\Item;

trait WindowTransactionTrait{

    protected $transaction = null;
    protected $itemTransaction = [];

    public function hasTransaction() : Bool{
        return (Bool) $this->transaction;
    }

    public function getTransaction() : ?Closure{
        return $this->transaction;
    }

    public function setTransaction(Closure $closure) : self{
        $this->transaction = $closure;
        return $this;
    }

    public function hasItemTransaction(Item $item) : Bool{
        return isset($this->itemTransaction[json_encode($item->jsonSerialize())]);
    }
    
    public function getItemTransaction(Item $item) : ?Closure{
        return $this->itemTransaction[json_encode($item->jsonSerialize())] ?? null;
    }

    public function setItemTransaction(Item $item, Closure $closure) : self{
        $this->itemTransaction[json_encode($item->jsonSerialize())] = $closure;
        return $this;
    }

    public function removeItemTransaction(Item $item) : self{
        if(isset($this->itemTransaction[json_encode($item->jsonSerialize())])){
           unset($this->itemTransaction[json_encode($item->jsonSerialize())]);
        }
        return $this;
    }

}