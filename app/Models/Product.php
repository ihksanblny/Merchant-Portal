<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'category',
        'description',
        'critical_stock',
        'expiry_days_threshold',
        'price',
    ];

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
