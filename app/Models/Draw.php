<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Draw extends Model
{
    protected $fillable = [
        'title',
        'description',
        'ticket_price',
        'total_pool',
        'max_tickets',
        'sold_tickets',
        'status',
        'winner_selected_at',
        'winner_selection_method',
        'seed_hash',
        'created_by',
        'start_time',
        'end_time',
        'draw_time',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'draw_time' => 'datetime',
        'winner_selected_at' => 'datetime',
        'ticket_price' => 'decimal:2',
        'total_pool' => 'decimal:2',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
