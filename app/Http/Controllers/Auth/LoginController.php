<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Mapping from the dropdown value submitted by the form to the actual
     * user_type values stored in the users table.
     */
    protected $roleTypeMap = [
        'student'    => ['student'],
        'teacher'    => ['teacher'],
        'parent'     => ['parent'],
        'admin'      => ['admin', 'accountant', 'librarian'],
        'super_admin'=> ['super_admin'],
    ];


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /*
     *  Login with Username or Email
     * */
    public function username()
    {
        $identity = request()->identity;
        $field = filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$field => $identity]);
        return $field;
    }

    /**
     * After a successful credential-check, verify the selected role matches
     * the user's actual user_type. Kick them out with a clear message if not.
     */
    protected function authenticated(Request $request, $user)
    {
        $selectedRole = $request->input('role', 'student');
        $allowedTypes = $this->roleTypeMap[$selectedRole] ?? [];

        if (!in_array($user->user_type, $allowedTypes)) {
            // Log the user out immediately
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();


            return redirect()->route('login')
                ->withInput($request->only('identity'))
                ->withErrors([
                    'identity' => 'Use Correct Login Credentials.'
                ]);
        }

        return redirect()->intended($this->redirectTo);
    }
}
