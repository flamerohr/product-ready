<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTransactionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->enum('type', ['Purchase', 'Application']);
            $table->integer('quantity');
            $table->decimal('unit_price');
            $table->timestamps();
        });
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->decimal('price');
            $table->foreignId('created_transaction_id')->references('id')->on('transactions');
            $table->foreignId('deleted_transaction_id')->references('id')->on('transactions');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('transactions');
    }
}
