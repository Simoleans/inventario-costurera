<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public $fillable = ['name_complete'];

     //scope active
     public function scopeActive($query)
     {
         return $query->where('status', 1);
     }

     public function data()
    {
        return $this->hasMany(Data::class, 'client_id');
    }
}
