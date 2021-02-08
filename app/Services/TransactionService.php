<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService {
    public function apply($quantity) {
        $transaction = Transaction::create([
            'date' => Carbon::now(),
            'type' => 'Application',
            'quantity' => $quantity,
        ]);

        // TODO: could shift this call out to a queue in future
        $this->processApplication($transaction);

        return $transaction;
    }

    public function purchase($quantity, $price) {
        $transaction = Transaction::create([
            'date' => Carbon::now(),
            'type' => 'Purchase',
            'quantity' => $quantity,
            'unit_price' => $price,
        ]);

        // TODO: could shift this call out to a queue in future
        $this->processPurchase($transaction);

        return $transaction;
    }

    public function processApplication($transaction) {
        $products = Product::orderBy('date', 'desc')->take($transaction->quantity)->get();

        if ($products->count() < $transaction->quantity) {
            // not enough
        }
        // TODO: potential race condition, investigate a better way to avoid this
        $transaction->applied()->save($products);

        $productIds = $products->map(function ($product) {
            return $product->id;
        });
        Product::where('id', $productIds)->delete();

        return $products;
    }

    public function processPurchase($transaction) {
        $products = [];

        for ($i = 0; $i < $transaction->quantity; $i++) {
            $products[] = new Product([
                'date' => $transaction->date,
                'price' => $transaction->unit_price,
            ]);
        }

        $transaction->purchased()->save($products);

        return $transaction;
    }
}
