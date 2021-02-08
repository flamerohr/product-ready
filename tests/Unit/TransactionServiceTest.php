<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TransactionService;
use App\Models\Product;
use App\Models\Transaction;

class TransactionServiceTest extends TestCase
{
    /**
     * Tests create application
     *
     * @return void
     */
    public function testCreateApplication()
    {
        // watch the processApplication method that it is called
        $service = $this->partialMock(TransactionService::class, function ($mock) {
            $mock->shouldReceive('processApplication')->once();
        });

        $transaction = $service->create([
            'date' => now(),
            'type' => 'Application',
            'quantity' => 1,
        ]);

        // make sure the transaction is committed
        $this->assertNotNull($transaction->id);
    }

    /**
     * Tests create purchase
     *
     * @return void
     */
    public function testCreatePurchase()
    {
        // watch the processPurchase method that it is called
        $service = $this->partialMock(TransactionService::class, function ($mock) {
            $mock->shouldReceive('processPurchase')->once();
        });

        $transaction = $service->create([
            'date' => now(),
            'type' => 'Purchase',
            'quantity' => 1,
        ]);

        // make sure the transaction is committed
        $this->assertNotNull($transaction->id);
    }

    public function testProcessPurchase()
    {
        // create a purchase transaction
        $transaction = Transaction::factory()->purchases()->make();

        $service = $this->app->get(TransactionService::class);

        $products = $service->processPurchase($transaction);

        // check for expected results
        $this->assertEquals(count($products), $transaction->quantity);
        $this->assertEquals($products[0]->price, $transaction->unit_price);
        $this->assertEquals($products[0]->date, $transaction->date);
    }

    public function testProcessApplication()
    {
        $purchases = Product::factory()->count(100)->create();

        $transaction = Transaction::factory()->applications()->make();

        $service = $this->app->get(TransactionService::class);

        $products = $service->processApplication($transaction);

        $this->assertEquals(count($products), $transaction->quantity);

        $product = Product::withTrashed()->where('id', $products[0]->id)->first();

        $this->assertNotNull($product->deleted_at);
    }
}
