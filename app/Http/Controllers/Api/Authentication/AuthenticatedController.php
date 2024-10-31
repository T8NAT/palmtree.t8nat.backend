<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse ;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\CompanyResource;

class AuthenticatedController extends Controller
{

    public function login(LoginRequest $request)
    {
        $credentials =['unique_id' => $request->unique_id, 'password' => $request->password,'role' => $request->role] ;

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $data['token'] = $user->createToken('AppDeals',['company'])->plainTextToken;
            $data['company_name'] = $user->name;
            $data['email'] = $user->email;
            $data['unique_id'] = $user->unique_id;
            return ApiResponse::sendResponse(200, 'Login Successfully', $data);
        } else {
            return ApiResponse::sendResponse(401, 'Error with your credentials', null);
        }
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'unique_id' => Str::random(10),
        ]);

        // set role
        $user->role        = $request->role;
        $user->save();

        if($user->role == 'company'){
            $company = Company::create([
                'user_id'  => $user->id
            ]);
        }
        $data['token']     = $user->createToken('AppDeals')->plainTextToken;
        $data['name']      = $user->name;
        $data['unique_id'] = $user->unique_id;
        $data['email']     = $user->email;

        return ApiResponse::sendResponse(201, 'Account Created Successfully', $data);
    }

    public function udpateLocation(Request $request){
        $request->validate([
            'lat' => 'required',
            'lng' => 'required'
        ]);
        $request->user()->update([
            'lat' => $request->input('lat'),
            'lng' => $request->input('lng'),
        ]);

        return ApiResponse::sendResponse(201, 'Location is updated Successfully',[]);
    }

    public function getMeProfile(Request $request){
        $user =  $request->user();

        if($user->role == 'company'){
            $resource = new CompanyResource($user);
        } else{
            $resource = new UserResource($user);
        }
        return response()->json([
            'data' => $resource
        ]);
    }

    public function logout(Request $request)
    {
        // Revoke the user's token
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
 }
