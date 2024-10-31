<?php

namespace App\Http\Controllers\Api;

use App\Models\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StatusResource;

class StatusController extends Controller
{
    //
    public function getStatus(){
        $status = Status::get();
        
        
        return response()->json([
            'status'  => 'success',
            'data'    =>  StatusResource::collection($status)
        ]);
    }
}
