<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'draw_id',
        'name',
        'description',
        'price',
        'entries_per_product',
        'image',
    ];

    public function draw()
    {
        return $this->belongsTo(Draw::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
