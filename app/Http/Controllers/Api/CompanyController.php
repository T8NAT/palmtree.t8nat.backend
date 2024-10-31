<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CompanyResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    //

    public function updateProfile(Request $request, User $user)
    {
        try {
            // Check if the logged-in company matches the company being updated
            if ($request->user()->id !== $user->id) {
                return response()->json(['error' => 'You do not have permission to update this company.'], 403);
            }

            $inputs = $request->only([
                'address',
                'city',
                'street',
                'postalCode',
                'neighbourhood',
                'address_lat',
                'address_lng',
                'sub_address',
                'sub_city',
                'sub_street',
                'sub_neighbourhood',
                'sub_postalCode',
                'sub_address_lat',
                'sub_address_lng'
            ]);

            $validator = Validator::make($inputs,[
                'address' => 'nullable|string',
                'city' => 'nullable|string',
                'street' => 'nullable|string',
                'neighbourhood' => 'nullable|string',
                'postalCode' => 'nullable|numeric'
            ]);
            

            if ($validator->fails()) {
                throw ValidationException::withMessages($validator->errors()->toArray());
            }

            $company = Company::where([
                'user_id' => $user->id
            ])->firstOrFail();

            $company->update($inputs);

            return response()->json(['message' => 'Company Updated Successfully']);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the company.'], 500);
        }
    }


    public function updateMyProfile(Request $request){
        return $this->updateProfile($request,auth()->user());
    }
}
