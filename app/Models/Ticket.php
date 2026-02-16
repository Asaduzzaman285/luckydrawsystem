<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number',
        'user_id',
        'draw_id',
        'transaction_id',
        'is_winner',
        'prize_tier_id',
        'prize_amount',
        'metadata',
        'purchase_price',
        'status',
    ];

    protected $casts = [
        'is_winner' => 'boolean',
        'prize_amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function draw()
    {
        return $this->belongsTo(Draw::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
