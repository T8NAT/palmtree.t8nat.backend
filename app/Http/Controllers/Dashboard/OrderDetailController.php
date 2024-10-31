<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Detail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class OrderDetailController extends Controller
{
    
    public function index() {

        $details =Detail::all();

        return view('dashboard.orders.details',compact('details'));


    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
    
        // Create a new OrderDetail 
        $Detail = Detail::create([
            'name' => $request->input('name'),
        ]);
    
  
        Alert::success('Success', "detail created Successfully");

        return redirect()->back();
    }

    public function destroy(Detail $detail, Request $request) {
        // No need to find $detail again as it's already passed as an instance
    
        $detail->delete();
    
        Alert::success('Success', 'Detail deleted');
    
        return redirect()->back();
    }
}
