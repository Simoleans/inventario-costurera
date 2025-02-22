<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;

    protected $fillable = ['employe_id', 'items_id', 'qty','date_register_id','client_id'];

    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }

    public function items()
    {
        return $this->belongsTo(Item::class);
    }

    public function dateRegister()
    {
        return $this->belongsTo(DateRegister::class, 'date_register_id');
    }

    public function client()
    {
        return $this->belongsTo(Customer::class, 'client_id');
    }



}
