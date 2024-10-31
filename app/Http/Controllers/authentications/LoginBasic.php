<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\RedirectResponse;


class LoginBasic extends Controller
{
  protected $redirectTo = 'dashboard';

  public function __construct()
  {
    $this->middleware('guest')->except('logout');
  }

  public function showLoginForm()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login-basic', ['pageConfigs' => $pageConfigs]);
  }

  protected function guard()
  {
    return Auth::guard('web');
  }

  protected function credentials(Request $request)
  {
    return $request->only($this->username(), 'password');
  }

  protected function login(Request $request)
  {
    $credentials = $this->credentials($request);

    // Check for admin role during login attempt

    return $this->guard()->attempt(
      $credentials,
      $request->filled('remember')
    );

    return redirect()->intended('dashboard');
  }


  public function authenticate(Request $request): RedirectResponse
  {
    $credentials = $request->validate([
      'unique_id' => ['required'],
      'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
      $request->session()->regenerate();

      if(auth()->user()->role == 'company'):
        return redirect()->intended('/my-orders');
      endif;
      return redirect()->intended('/');
    }

    return back()->withErrors([
      'unique_id' => 'The provided credentials do not match our records.',
    ])->onlyInput('unique_id');
  }

  public function logout(Request $request)
  {
    $this->guard()->logout();

    $request->session()->invalidate();

    return redirect('/login'); // Change the redirect path after logout as needed
  }



  public function username()
  {
    return 'unique_id';
  }


  protected function redirectTo(Request $request): string
{
    return route('login');
}
}
