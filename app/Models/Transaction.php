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

    public function purchased() {
        return $this->hasMany(Product::class, 'created_transaction_id');
    }

    public function applied() {
        return $this->hasMany(Product::class, 'deleted_transaction_id');
    }
}
