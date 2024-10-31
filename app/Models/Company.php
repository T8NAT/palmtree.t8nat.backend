<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Company extends Authenticatable
{
    use HasFactory , HasApiTokens , Notifiable;
    
    protected $fillable = [
        'user_id',
        'address',
        'city',
        'street',
        'neighbourhood',
        'address_lat',
        'address_lng',
        'postalCode',
        'sub_address',
        'sub_city',
        'sub_street',
        'sub_neighbourhood',
        'sub_address_lat',
        'sub_address_lng',
        'sub_postalCode'
    ];
    

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
