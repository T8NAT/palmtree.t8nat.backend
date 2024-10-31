<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','order_id','lat','long'];

    public function order()
    {
       return $this->belongsTo(Order::class,'order_id','id');
    }

    public function delivery()
    {
       return $this->belongsTo(User::class,'user_id','id');
    }
}
