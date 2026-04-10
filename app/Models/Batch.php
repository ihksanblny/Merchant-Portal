<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = [
        'product_id',
        'batch_code',
        'expire_date',
        'initial_qty',
        'current_qty',
    ];

    /**
     * Relasi balik ke Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
