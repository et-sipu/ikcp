<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $maxAttempts = 5;

    protected $decayMinutes = 10;

    /**
     * RegisterController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password') + ['active' => 1];
    }

    /**
     * Show the application's login form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm(Request $request)
    {
        return view('auth.admin.login')
            ->withIsLocked($this->hasTooManyLoginAttempts($request));
    }

    /**
     * Get the throttle key for the given request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    protected function throttleKey(Request $request)
    {
        return Str::lower($request->ip());
    }

    /**
     * Validate the user login request.
     *
     * @param \Illuminate\Http\Request $request
     */
    protected function validateLogin(Request $request)
    {
        $rules = [
            $this->username() => 'required|string',
            'password'        => 'required|string',
        ];

        if ($this->hasTooManyLoginAttempts($request)) {
            $rules['g-recaptcha-response'] = ['required', 'captcha'];
        }

        $this->validate($request, $rules);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->clearLoginAttempts($request);
        }
    }

    private function flushSession(Request $request, $redirectTo = 'home')
    {
        if ($admin_id = session()->get('admin_user_id')) {
            // Impersonate mode, back to original User
            session()->forget('admin_user_id');
            session()->forget('admin_user_name');
            session()->forget('temp_user_id');

            auth()->loginUsingId((int) $admin_id);

            return redirect()->route('home');
        }

        // Normal logout
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect()->route($redirectTo);
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     *
     * @throws \RuntimeException
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        return $this->flushSession($request, 'login');
    }

    /**
     * @return string
     */
    protected function redirectTo()
    {
        return home_route();
    }
}
