<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Order;
use App\Models\Proposal;
use App\enum\OrderStatus;
use App\Models\OrderUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\NotificationsServices;
use RealRashid\SweetAlert\Facades\Alert;


class OrderUserController extends Controller
{

    public function showOrdersWithOffers()
    {
        // Get orders that have offers
        $ordersWithOffers = Order::has('proposals')->withCount('proposals')->orderBy('created_at', 'desc')->paginate(8);
        return view('dashboard.offers.orders_with_offers', compact('ordersWithOffers'));
    }

    public function showOffersForOrder($orderId)
    {
        // Get all offers for the order
        $offers = Proposal::with(['delivery'=>function($query){
            $query->withCount(['orders as orders_in_holds' => function($query){
                $query->whereNotIn('order_status',[OrderStatus::Delivered,OrderStatus::Canceled,OrderStatus::Returned]);
            },'orders as orders_finished' => function($query){
                $query->whereIn('order_status',[OrderStatus::Delivered,OrderStatus::Canceled,OrderStatus::Returned]);
            }]);
        }])->where([
            'order_id' => $orderId
        ])->get();

        return view('dashboard.offers.show_offers', compact('offers'));
    }

    public function acceptOffer(Request $request, $offerId)
    {
        // Find the Proposal record
        $Proposal = Proposal::find($offerId);

        // Update the chosen column
        $Proposal->update(['approved' => 'approved']);

        // un approve all rest orders
        Proposal::where([
            ['order_id','=',$Proposal->order_id],
            ['id','!=',$offerId],
            ['approved','=','wait']
        ])->update(['approved' => 'unapproved']);

        // Update the associated order's status to "Accepted"
        $order = $request->order_id;
        $order = Order::findOrFail($order);
        $order->update([
            'order_status'         => 'Accepted',
            'selected_delivery_id' => $Proposal->delivery_id
        ]);

        try{
            NotificationsServices::DeliveryProposalAccepted($order,$Proposal->delivery);
        } catch(\Exception $e){}

        // Redirect back or to a specific route
        Alert::success('Accepted', "Delivery Accepted Succussfully");

        return redirect()->route('offers.show_orders_with_offers');
    }

    public function notifiyOrderIsTolate(Order $order){
        try{
            NotificationsServices::DeliveryOrderIsToLated($order,$order->delivery);
        } catch(\Exception $e){}

        // Redirect back or to a specific route
        Alert::success('sent', "Notification is sent successfully");
        return back();
    }
}
