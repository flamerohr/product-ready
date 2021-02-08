<?php

namespace App\Services;

use App\Models\Transaction;

/**
 * The service for handling all the business logic for the transactions
 */
class TransactionService {
    /**
     * For processing a current application for a product
     */
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

    /**
     * For processing a current purchase for a product
     */
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

    /**
     * Create a new transaction and handle post-creation tasks
     */
    public function create($data) {
        $transaction = Transaction::create($data);
        $transaction->save();

        // TODO: this part could be in a Model Event listener I think
        switch ($transaction->type) {
            case 'Purchase':
                $this->processPurchase($transaction);
                break;
            case 'Application':
                $this->processApplication($transaction);
                break;
            default:
                // something missing?
        }

        return $transaction;
    }

    /**
     * Process an application
     */
    public function processApplication($transaction) {
        $products = Product::orderBy('date', 'desc')->take($transaction->quantity)->get();

        $count = $products->count();
        if ($count < $transaction->quantity) {
            // not enough
            throw new InsufficientProductsException($count);
        }

        // TODO: potential race condition, investigate a better way to avoid this
        $transaction->applied()->save($products);

        // TODO: potentially move to Model Event listener I think
        $productIds = $products->map(function ($product) {
            return $product->id;
        });
        Product::where('id', $productIds)->delete();

        return $products;
    }

    /**
     * Process a purchase
     */
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
