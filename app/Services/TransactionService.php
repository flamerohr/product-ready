<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Product;

/**
 * The service for handling all the business logic for the transactions
 */
class TransactionService {
    /**
     * For processing a current application for a product
     */
    public function apply($quantity) {
        $transaction = $this->create([
            'date' => Carbon::now(),
            'type' => 'Application',
            'quantity' => $quantity,
        ]);

        return $transaction;
    }

    /**
     * For processing a current purchase for a product
     */
    public function purchase($quantity, $price) {
        $transaction = $this->create([
            'date' => Carbon::now(),
            'type' => 'Purchase',
            'quantity' => $quantity,
            'unit_price' => $price,
        ]);

        return $transaction;
    }

    /**
     * Create a new transaction and handle post-creation tasks
     */
    public function create($data) {
        $transaction = Transaction::create($data);
        $transaction->save();

        // TODO: this part could be in a Model Event listener and a queue I think
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
        $products = Product::getBatch(abs($transaction->quantity));

        // TODO: could be better to query a count first, and get the list after this check
        // check that there's enough products available
        $count = $products->count();
        if ($count < $transaction->quantity) {
            // not enough products to go around
            throw new InsufficientProductsException($count);
        }

        // TODO: potential race condition with getBatch above and delete below, investigate a better way to avoid this
        $transaction->applied()->saveMany($products);

        // TODO: potentially move to Model Event listener I think
        $productIds = $products->map(function ($product) {
            return $product->id;
        });

        // remove products that are no longer available
        $deleted = Product::whereIn('id', $productIds->all())->delete();

        if ($deleted !== $count) {
            throw new InvalidProductRemovalException($transaction);
        }
        return $products;
    }

    /**
     * Process a purchase
     */
    public function processPurchase($transaction) {
        $products = [];

        for ($i = 0; $i < $transaction->quantity; $i++) {
            // create each new product according to the purchase
            $products[] = new Product([
                'date' => $transaction->date,
                'price' => $transaction->unit_price,
            ]);
        }

        $transaction->purchased()->saveMany($products);

        return $transaction;
    }
}
