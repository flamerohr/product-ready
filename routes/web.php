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

// TODO: move these routes to a Controller
Route::get('/', function () {
    return view('product-form');
});

Route::post('/', function (TransactionService $transactionService, Request $request) {
    $request->validate([
        'quantity' => 'required|numeric',
    ]);
    $quantity = $request->input('quantity');
    $amount = Product::getBatchPrice($quantity);

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

    $amount = $transaction->appliedPrice();

    return view('product-applied', [
        'amount' => $amount,
        'quantity' => $quantity,
    ]);
});
