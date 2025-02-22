<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DateRegister extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'status'];

    public $table = 'date_registers';

    public function data()
    {
        return $this->hasMany(Data::class, 'date_register_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
