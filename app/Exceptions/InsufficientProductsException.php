<?php

namespace App\Exceptions;

use Exception;

class InsufficientProductsException extends Exception {
    public $remaining_count;

    public function __contruct($remainingCount) {
        $this->remaining_count = $remainingCount;
    }
}
