<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = [
        'user_id',
        'motorcycle_id',
        'rental_date',
        'return_date',
        'total_cost',
        'status',

        // Payment records
        'down_payment',
        'deposit_amount',
        'payment_status',
        'deposit_status',
    ];

    protected $casts = [
        'rental_date' => 'date',
        'return_date' => 'date',
        'total_cost' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function motorcycle()
    {
        return $this->belongsTo(Motorcycle::class);
    }
}