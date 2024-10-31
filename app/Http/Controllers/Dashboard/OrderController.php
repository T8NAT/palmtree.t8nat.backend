<?php

namespace App\Http\Controllers\Dashboard;

use Exception;
use App\Models\User;
use App\Models\Order;
use App\Models\Detail;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Services\NotificationsServices;
use App\Services\PrintOrderInvoice;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str; // Import Str class for string manipulation

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->paginate(8);
        return view('dashboard.orders.index', compact('orders'));
    }

    public function search(Request $request) 
    {
        $searchTerm = $request->input('search');
        $orders = Order::where('location_name', 'LIKE', "%$searchTerm%")
                       ->where('destination_name', 'LIKE', "%$searchTerm%")
                       ->orWhere('seller_name', 'LIKE', "%$searchTerm%")
                       ->orWhere('customer_name', 'LIKE', "%$searchTerm%")
                       ->orWhere('customer_notes', 'LIKE', "%$searchTerm%")
                       ->orWhere('order_status', 'LIKE', "%$searchTerm%")
                       ->orWhere('insrtuctions', 'LIKE', "%$searchTerm%")
                       ->paginate();
        return view('dashboard.orders.index', ['orders' => $orders]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies  = Company::all();
        $deliveries = User::Delivery()->select('id','name')->get();
        return view('dashboard.orders.create', compact('companies','deliveries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_id'      => 'required|exists:companies,id',
            'seller_name'     => 'required|string',
            'location_lat'    => 'required|string',
            'location_lng'    => 'required|string',
            'location_name'   => 'required|string',
            'customer_name'   => 'required|string',
            'customer_phone'  => 'required|string',
            'destination_lat' => 'required|string',
            'destination_lng' => 'required|string',
            'destination_name' => 'required|string',
            'location_full_address' => 'required|string',
            'destination_full_address' => 'required|string',
            'customer_notes'  => 'nullable|string',
            'attachment'      => 'nullable|file|mimes:jpeg,png,pdf,docx|max:16048',
        ]);

        if($request->has('selected_deliveries')){
            $request->merge([
                'selected_deliveries' => json_encode($request->input('selected_deliveries'))
            ]);
        }

        $data = $request->only([
            'company_id',
            'seller_name',
            'location_lat',
            'location_lng',
            'location_name',
            'customer_name',
            'customer_phone',
            'destination_lat',
            'destination_lng',
            'destination_name',
            'location_full_address',
            'destination_full_address',
            'customer_notes',
            'selected_deliveries'
        ]);

        
        // Generate a unique ID with the specified format
        $randomLetters = strtoupper(Str::random(2));
        $randomNumbers = mt_rand(100000, 999999);
        $uniqueId = $randomLetters . $randomNumbers;
        $data['unique_id'] = $uniqueId;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('attachments', $fileName, 'public');
            $data['attachment'] = $fileName;
        }

        $order = Order::create($data);
        $no_order = $request->has('no_orders') ? $request->input('no_orders') : 1;
        if($no_order > 1):
            set_time_limit(100000);
            for($i = 1;$i <= $no_order; $i++):
                $new_order = $order->replicate();
                $new_order->unique_id = $this->generate_uniqueKey();
                $new_order->save();
            endfor;
        endif;

        try{
            // send notification to deliveries
            $selected_deliveries = isset($data['selected_deliveries']) ? $data['selected_deliveries'] : null;
            NotificationsServices::deliveryOrderCreated($order,$selected_deliveries);
        } catch(Exception $e){
            Log::error($e->getMessage());
        }

        // Validation passed, create the order
        Alert::success('success', "Orders are created successfully");
        return redirect()->route('orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $unique_id)
    {
        //
        $order = Order::with('delivery','company','company.user','trackings')
        ->withCount('proposals')->where([
            'unique_id' => $unique_id
        ])->first();
        return view('dashboard.orders.details',compact('order'));
    }

    public function ajaxTrackings(string $unique_id){
        $order = Order::with('trackings')->where([
            'unique_id' => $unique_id
        ])->first();

        return response()->json([
            'order' => $order
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $companies = Company::all();
        $deliveries = User::Delivery()->select('id','name')->get();
        return view('dashboard.orders.edit', compact('order', 'companies','deliveries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'seller_name' => 'required|string',
            'location_lat'    => 'required|string',
            'location_lng'    => 'required|string',
            'location_name'   => 'required|string',
            'customer_name'   => 'required|string',
            'customer_phone'  => 'required|string',
            'destination_lat' => 'required|string',
            'destination_lng' => 'required|string',
            'destination_name' => 'required|string',
            'location_full_address' => 'required|string',
            'destination_full_address' => 'required|string',
            'customer_notes' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,pdf,docx|max:2048',
            'insrtuctions' => 'nullable|array',
        ]);

        $insrtuctions = null;
        if($request->has('insrtuctions')):
            $prev_instructions = [];
            if($order->insrtuctions !== null):
                $prev_instructions   = explode(',',$order->insrtuctions);
            endif;
            $new_instructions    = array_diff($request->input('insrtuctions'),$prev_instructions ?: []);
            $insrtuctions        = implode(',',$request->input('insrtuctions'));
            try{
                NotificationsServices::InstructionsOrderChenged($new_instructions,$order,$order->company->user);
            } catch(Exception $e){
                Log::error($e->getMessage());
            }
        endif;

        $request->merge([
            'insrtuctions' => $insrtuctions
        ]);

        $selected_deliveries = null;
        if($request->has('selected_deliveries')){
            $selected_deliveries = json_encode($request->input('selected_deliveries'));
        }

        $request->merge([
            'selected_deliveries' => $selected_deliveries
        ]);

        $data = $request->only([
            'company_id',
            'seller_name',
            'location_lat',
            'location_lng',
            'location_name',
            'customer_name',
            'customer_phone',
            'destination_lat',
            'destination_lng',
            'destination_name',
            'location_full_address',
            'destination_full_address',
            'customer_notes',
            'insrtuctions',
            'attachment',
            'selected_deliveries'
        ]);

        if ($request->hasFile('attachment') && $request->file('attachment') !== null) {
            // If there is a new file, delete the old one (if it exists)
            if ($order->attachment) {
                Storage::disk('public')->delete('attachments/' . $order->attachment);
            }

            // Upload the new file
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('attachments', $fileName, 'public');
            $data['attachment'] = $fileName;
        } else {
            // If no new file is uploaded, keep the existing file
            $data['attachment'] = $order->attachment;
        }

        $order->update($data);

        Alert::success('Order Updated Successfully');
        return redirect()->route('orders.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        Alert::success('Success', "Order Deleted Successfully");
        return redirect()->route('orders.index');
    }

    public function generate_uniqueKey(){
        $randomLetters = strtoupper(Str::random(2));
        $randomNumbers = mt_rand(100000, 999999);
        $uniqueId = $randomLetters . $randomNumbers;
        return $uniqueId;
    }

    public function print_order($unique_id){
        PrintOrderInvoice::printOrder($unique_id,'FI');
    }

    public function show_invoice_pdf($unique_id){
        $file = \File::get(public_path('orders_invoices/'.'بوليصة-رقم-'.$unique_id.'.pdf'));
        $response = \Response::make($file, 200);
        $response->header('Content-Type', 'application/pdf');
        return $response;
    }
}
