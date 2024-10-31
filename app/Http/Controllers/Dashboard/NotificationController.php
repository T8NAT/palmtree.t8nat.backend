<?php

namespace App\Http\Controllers\Dashboard;

use Exception;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\NotificationsServices;
use App\Services\SendFireBaseNotification;
use App\Http\Resources\NotificationResource;
use RealRashid\SweetAlert\Facades\Alert;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $rows = request('rows') ?: 10;
        $users = User::select('name','id','role')->get();
        $notifications = DB::table('notifications')->orderBy('created_at','desc')
        ->leftJoin('users','notifications.notifiable_id','=','users.id')
        ->select('notifications.*','users.name as user_name','users.role as user_role','users.unique_id as user_id')->paginate($rows);
        return view('dashboard.notifications.index', compact('notifications','users'));
    }

    /**
     * Display a listing of the resource.
     */
    public function push_notification(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body'  => 'required',
            'type'  => 'required'
        ]);

        $notification = [
            'title' => $request->input('title'),
            'body' => $request->input('body')
        ];

        if($request->input('type') == 'all'){
            $users = User::where('role','!=','admin')->get();
        } elseif($request->input('type') == 'all-company'){
            $users = User::where('role','company')->get();
        } elseif($request->input('type') == 'all-delivery'){
            $users = User::where('role','delivery')->get();
        } else{
            $users = User::where('id',$request->input('type'))->get();
        }

        try{
            NotificationsServices::CustomNotification($users,$notification);
            Alert::success('Notification is sent successfully');
            return back();
        } catch(Exception $e){
            Alert::failed('Notification failed to send');
            return back();
        }
    }
}
