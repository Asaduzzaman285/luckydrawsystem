<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    protected $fillable = [
        'user_id',
        'target_agent_id',
        'amount',
        'payment_method',
        'account_details',
        'status',
        'admin_notes',
        'processed_by',
        'processed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function targetAgent()
    {
        return $this->belongsTo(User::class, 'target_agent_id');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
