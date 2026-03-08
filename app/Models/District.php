<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function agents()
    {
        return $this->belongsToMany(User::class, 'district_user');
    }

    public function upazillas()
    {
        return $this->hasMany(Upazilla::class);
    }
}
