<?php

namespace App\Http\Controllers\Api\Salla;

use Exception;
use App\Models\User;
use Faker\Core\Uuid;
use App\Models\SallaPayload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class SallaController extends Controller
{

  public function getPayload(Request $request)
  {

    try {
      $data = json_decode($request->getContent(), true);
      $payload = SallaPayload::create([
        'access_token' => $data['data']['access_token'],
        'expires' => $data['data']['expires'],
        'refresh_token' => $data['data']['refresh_token'],
        'scope' => $data['data']['scope'],
        'token_type' => $data['data']['token_type'],
      ]);

      $response = Http::withHeaders([
        "Accept" => "application/json",
        'Content-Type' => "application/json",
        'Authorization' => 'Bearer ' . $payload->access_token,
      ])->get('https://accounts.salla.sa/oauth2/user/info');

      if ($response->successful()) {
        $response_data = $response->json()['data'];
        DB::transaction(function () use ($response_data) {
          $user = User::create([
            'name' => $response_data['name'],
            'email' => $response_data['email'],
            'password' => Hash::make('123456'),
            'unique_id' => uniqid(),
            'role' => 'company',
          ]);
          if ($user) {
            $user->company_info()->create([
              'address' => 'test',
              'city' => 'test',
              'street' => 'test',
              'neighbourhood' => 'test',
            ]);
          }
        });

        return response()->json(['success' => true, 'message' => 'User created successfully']);
      }

      return response()->json(['success' => false, 'error' => 'Failed to fetch user info from Salla'], 500);
    } catch (Exception $e) {

      return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
  }
}
