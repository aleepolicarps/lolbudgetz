<?php

namespace App\Http\Controllers\Auth;

use App\Blacklist;
use App\RegisterAttempt;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function attempt_register(Request $request) {
        $user = User::where('email', $request->input('email_address'))->first();
        if($user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'The email you provided is already associated to an account.'
            ], 400);
        }

        $blacklist = Blacklist::where('email_address', $request->input('email_address'))->first();
        if($blacklist) {
            return response()->json([
                'status' => 'failed',
                'message' => 'You are forbidden to signup to this website.'
            ], 403);
        }

        $attempt = new RegisterAttempt;
        $attempt->first_name = $request->input('first_name');
        $attempt->last_name = $request->input('last_name');
        $attempt->email_address = $request->input('email_address');
        $attempt->save();

        return response()->json([
            'status' => 'success',
            'register_attempt_id' => $attempt->id
        ]);
    }
}
