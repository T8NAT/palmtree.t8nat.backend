<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public function index()
    {


        $users = User::Delivery()->orderBy('created_at', 'desc')->paginate(8);

        return view('dashboard.users.index', compact('users'));
    }


    public function create()
    {

        return view('dashboard.users.create');
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'unique_id' => Str::random(10),
        ]);

        Alert::success('Unique ID', $user->unique_id);



        return redirect()->route('users.index')->with('success', 'User Added Successfully');
    }


    public function edit(User $user)
    {


        return view('dashboard.users.edit', compact('user'));
    }




    public function update(Request $request, User $user)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'unique_id' => 'required|string|size:9|unique:users,unique_id,' . $user->id,
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->name = $request->name;
        $user->email = $request->email;

        $user->unique_id = $request->unique_id;

        $user->save();

        Alert::success('Success', "User Updated Successfully");


        return redirect()->route('users.index');
    }




    public function destroy(User $user)
    {


        $user->delete();

        Alert::success('Success', "User Deleted Successfully");

        return redirect()->route('users.index');
    }
}
