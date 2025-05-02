<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderStatus;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    protected $fillable = ['customer_id', 'product_name', 'quantity', 'price', 'status'];
    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
