<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Services\TransactionService;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('product-form');
});

Route::post('/', function (Request $request) {
    $request->validate([
        'quantity' => 'required|numeric',
    ]);
    $quantity = $request->input('quantity');
    $amount = Product::getBatch($quantity)
        ->map(function ($product) {
            return $product->price;
        })
        ->sum();

    return view('product-form', [
        'amount' => $amount,
        'quantity' => $quantity,
    ]);
});

Route::post('/apply', function (TransactionService $transactionService, Request $request) {
    $request->validate([
        'quantity' => 'required|numeric',
    ]);
    $quantity = $request->input('quantity');

    $transaction = $transactionService->apply($quantity);

    $products = $transaction->applied()->withTrashed()->get();

    $amount = $products
        ->map(function ($product) {
            return $product->price;
        })
        ->sum();

    return view('product-applied', [
        'amount' => $amount,
        'quantity' => $quantity,
    ]);
});
