<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'passport', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];


    //scope active employe
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function data()
    {
        return $this->hasMany(Data::class);
    }
}
