<?php

namespace DayKoala\inventory\action;

use Closure;

trait WindowTransactionTrait{

    protected $transaction = null;

    public function hasTransaction() : Bool{
        return (Bool) $this->transaction;
    }

    public function getTransaction() : ?Closure{
        return $this->transaction;
    }

    public function setTransaction(Closure $closure) : Void{
        $this->transaction = $closure;
    }

}