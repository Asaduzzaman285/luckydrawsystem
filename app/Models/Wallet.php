<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
        'lifetime_deposit',
        'lifetime_withdrawal',
        'last_transaction_at',
    ];

    protected $casts = [
        'last_transaction_at' => 'datetime',
        'balance' => 'decimal:2',
        'lifetime_deposit' => 'decimal:2',
        'lifetime_withdrawal' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
