<?php

namespace App\Models;

use App\Models\Detail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'selected_delivery_id',
        'location_lat',
        'location_lng',
        'location_name',
        'location_full_address',
        'destination_full_address',
        'destination_lat',
        'destination_lng',
        'destination_name',
        'seller_name',
        'customer_name',
        'customer_phone',
        'customer_notes',
        'order_status',
        'attachment',
        'insrtuctions',
        'unique_id',
        'serialNo',
        'selected_deliveries'
    ];

    public function delivery(){
       return $this->belongsTo(User::class,'selected_delivery_id','id');
    }

    public function company(){
       return $this->belongsTo(Company::class,'company_id','id');
    }


    public function proposals(){
        return $this->hasMany(Proposal::class,'order_id','id');
    }

    public function trackings(){
        return $this->hasMany(Tracking::class,'order_id','id');
    }


    // Order.php model
    public function details()
    {
        return $this->belongsToMany(Detail::class, 'order_detail', 'order_id', 'detail_id');
    }

}
