<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

/**
 * A transactional record of what has happened, could be useful for auditing the history of changes made.
 */
class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'type',
        'quantity',
        'unit_price',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'unit_price' => 0,
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
     * Products created if this was a purchase transaction
     */
    public function purchased() {
        return $this->hasMany(Product::class, 'created_transaction_id');
    }

    /**
     * Products applied for if this was an application transaction
     */
    public function applied() {
        return $this->hasMany(Product::class, 'deleted_transaction_id');
    }

    /**
     * The total price the applied products cost
     */
    public function appliedPrice() {
        // TODO: I think I could improve this to a query
        return $this->applied()
            ->withTrashed()
            ->get()
            ->map(function ($product) {
                return $product->price;
            })
            ->sum();
    }
}
