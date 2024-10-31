<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = ['delivery_id','order_id','status','approved'];

    public function delivery()
    {
       return $this->belongsTo(User::class,'delivery_id','id');
    }

    public function order()
    {
       return $this->belongsTo(Order::class,'order_id','id');
    }
}
