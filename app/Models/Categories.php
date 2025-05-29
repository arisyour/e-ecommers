<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Categories extends Model
{
    use HasUlids;

    protected $table = 'categories';

    protected $fillable = [
        'product_id',
        'name',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'product_id' => 'string',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}