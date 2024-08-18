<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = ['symbol', 'amount', 'average_buy_price'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
