<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;


class CompanyController extends Controller
{
    public function index()
    {

        $companies = User::Company()->with('company_info')->orderBy('created_at', 'desc')->paginate(8);

        return view('dashboard.companies.index', compact('companies'));
    }


    public function create()
    {

        return view('dashboard.companies.create');
    }



    public function store(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'required|string',
            'city' => 'required|string',
            'street' => 'required|string',
            'neighbourhood' => 'required|string',
            'postalCode' => 'required|numeric'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'unique_id'=> Str::random(10),
            'password' => Hash::make($request->password)
        ]);

        $user->role = 'company';
        $user->save();

        $company = Company::create([
            'user_id'  => $user->id,
            'address'  => $request->address,
            'city'     => $request->city,
            'street'   => $request->street,
            'postalCode'   => $request->postalCode,
            'neighbourhood' => $request->neighbourhood,
            'address_lat' => $request->address_lat,
            'address_lng' => $request->address_lng,
            'sub_address'   => $request->sub_address,
            'sub_city'      => $request->sub_city,
            'sub_street'    => $request->sub_street,
            'sub_neighbourhood' => $request->sub_neighbourhood,
            'sub_postalCode'    => $request->sub_postalCode,
            'sub_address_lat' => $request->sub_address_lat,
            'sub_address_lng' => $request->sub_address_lng
        ]);


        Alert::success('Unique ID', $company->unique_id);



        return redirect()->route('companies.index')->with('success', 'Company Added Successfully');
    }

    public function edit(Company $company)
    {
        return view('dashboard.companies.edit', compact('company'));
    }


    public function update(Request $request,Company $company)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $company->user_id,
            'address' => 'required|string',
            'city' => 'required|string',
            'street' => 'required|string',
            'neighbourhood' => 'required|string',
            'postalCode' => 'required|numeric'
        ]);
   

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user               = User::find($company->user_id);
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->save();

        // address
        $company->address      = $request->address;
        $company->city         = $request->city;
        $company->street       = $request->street;
        $company->neighbourhood= $request->neighbourhood;
        $company->postalCode   = $request->postalCode;
        $company->address_lat   = $request->address_lat;
        $company->address_lng   = $request->address_lng;

        // sub_address
        $company->sub_address       = $request->sub_address;
        $company->sub_city          = $request->sub_city;
        $company->sub_street        = $request->sub_street;
        $company->sub_neighbourhood = $request->sub_neighbourhood;
        $company->sub_postalCode    = $request->sub_postalCode;
        $company->sub_address_lat   = $request->sub_address_lat;
        $company->sub_address_lng   = $request->sub_address_lng;
        $company->save();

        

        Alert::success('Success', "Company Updated Successfully");


        return redirect()->route('companies.index');
    }


    public function destroy(Company $company)
    {
        $company->delete();
        Alert::success('Success', "Company Deleted Successfully");
        return redirect()->route('companies.index');
    }
}
