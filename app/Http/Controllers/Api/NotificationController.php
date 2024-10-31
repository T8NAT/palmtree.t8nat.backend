<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\SendFireBaseNotification;
use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $rows = request('rows') ?: 10;
        $user = auth()->user();
        $notifications = DB::table('notifications')->where('notifiable_id',$user->id)->paginate($rows);

        return response()->json([
            'notifications' => NotificationResource::collection($notifications)->resource
        ]);
    }
}
