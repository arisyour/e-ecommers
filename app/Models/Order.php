<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasUlids;

    protected $table = 'orders';

    protected $fillable = [
        'customer_id',
        'order_date',
        'total_amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'customer_id' => 'string',
            'order_date' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}