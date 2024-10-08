<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Investment extends Model
{
    use HasFactory;

    protected $table = 'investments';

    protected $fillable = ['user_id', 'symbol', 'amount', 'average_buy_price'];

    protected $casts = [
        'amount' => 'decimal:8',
        'average_buy_price' => 'decimal:8',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
