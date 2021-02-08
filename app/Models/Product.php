<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Exceptions\InvalidNumberException;

/**
 * A record of an individual product available for applying for.
 */
class Product extends Model
{
    use HasFactory,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'price',
        'created_transaction_id',
        'deleted_transaction_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Gets the next batch of products available for application
     */
    public static function getBatch($quantity) {
        if ($quantity < 0) {
            throw new InvalidNumberException();
        }
        return static::whereNull('deleted_transaction_id')
            ->orderBy('date', 'desc')
            ->limit($quantity)
            ->get();
    }

    public static function getBatchPrice($quantity) {
        // TODO: I think I could improve this to a query
        return static::getBatch($quantity)
            ->map(function ($product) {
                return $product->price;
            })
            ->sum();
    }
}
