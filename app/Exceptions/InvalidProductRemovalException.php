<?php

namespace App\Exceptions;

use Exception;
use App\Models\Transaction;

class InvalidProductRemovalException extends Exception {
    public $transaction;

    public function __contruct(Transaction $transaction) {
        $this->transaction = $transaction;
    }
}
