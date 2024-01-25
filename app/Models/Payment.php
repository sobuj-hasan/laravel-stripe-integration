<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'plan_id',
        'subscription_id',
        'stripe_id',
        'stripe_status',
        'quantity',
        'validity',
        'interval_count',
        'amount',
        'status',
    ];
}
