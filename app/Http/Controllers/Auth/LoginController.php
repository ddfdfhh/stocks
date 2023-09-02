<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display login page.
     *
     * @return Renderable
     */
    public function show()
    {

        $data['title'] = 'Login';
        return view('auth.login', with($data));
    }

    /**
     * Handle account login request
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $cr = ['email' => $request->email, 'password' => $request->password];

        if (Auth::attempt($cr)) {
  return createResponse(true, 'Admin Logged In Successfully', route('admin.dashboard'));
          /*  if (auth()->user()->role == 2) {
                if (is_null(auth()->user()->email_verified_at)) {
                    Auth::logout();
                    return createResponse(false, 'Please verify your account from email sent, while registering');
                }
                return createResponse(true, 'Logged In Successfully', route('dashboard'));
            } else {
                return createResponse(true, 'Admin Logged In Successfully', route('admin.dashboard'));
            }
*/
        } else {
            // dd(auth()->id());
            return createResponse(false, 'Login credentials are invalid');

        }

    }

    /**
     * Handle response after user authenticated
     *
     * @param Request $request
     * @param Auth $user
     *
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user)
    {
        return redirect()->intended();
    }
    public function logout(){
          Auth::logout();
          return redirect(route('login'));
    }
}
