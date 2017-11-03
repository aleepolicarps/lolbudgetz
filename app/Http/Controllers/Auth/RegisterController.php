<?php

namespace App\Http\Controllers\Auth;

use App\Blacklist;
use App\RegisterAttempt;
use App\SaleTransaction;
use App\User;
use App\Http\Controllers\Controller;
use App\Services\UserSubscriptionsHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

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
    private $user_subscription_handler;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserSubscriptionsHandler $user_subscription_handler)
    {
        $this->middleware('guest');
        $this->user_subscription_handler = $user_subscription_handler;
    }

    public function showRegistrationForm() {
        return redirect(route('signup'));
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

    public function showSignup($web_id='web_id_1') {
        if(Auth::check()) {
            return redirect(route('home'));
        } else {
            return view('signup', ['web_id' => $web_id]);
        }
    }

    public function complete_signup(Request $request)
    {
        $uuid = $request->input('uuid');

        if(User::where('uuid', $uuid)->first()) {
            abort(400, 'You have already completed your signup. Please login to check your account');
        }

        $register_attempt = RegisterAttempt::where('uuid', $uuid)->first();
        if(!$register_attempt) {
            abort(403, 'This URL is invalid. You have not yet signed up');
        }

        $sale_transaction = SaleTransaction::where('uuid', $uuid)->first();
        if(!$sale_transaction or !$sale_transaction->is_successful()) {
            abort(400, 'Please complete your payment.');
        }

        $user = new User;
        $user->first_name = $register_attempt->first_name;
        $user->last_name = $register_attempt->last_name;
        $user->email = $register_attempt->email_address;
        $user->uuid = $register_attempt->uuid;
        $user->password = bcrypt($request->input('password'));
        $user->save();

        $this->user_subscription_handler->start_user_trial($user);

        return response()->json([
            'status' => 'success'
        ]);
     }

    public function attempt_register(Request $request)
    {
        $attempt = new RegisterAttempt;
        $attempt->first_name = $request->input('first_name');
        $attempt->last_name = $request->input('last_name');
        $attempt->email_address = $request->input('email_address');
        $attempt->uuid = uniqid('user', true);
        $attempt->save();

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

        return response()->json([
            'status' => 'success',
            'uuid' => $attempt->uuid
        ]);
    }

    public function show_complete_signup($uuid)
    {
        if(User::where('uuid', $uuid)->first()) {
            abort(400, 'You have already completed your signup. Please login to check your account');
        }

        $register_attempt = RegisterAttempt::where('uuid', $uuid)->first();
        if(!$register_attempt) {
            abort(403, 'This URL is invalid. You have not yet signed up');
        }

        $sale_transaction = SaleTransaction::where('uuid', $uuid)->first();
        if(!$sale_transaction or !$sale_transaction->is_successful()) {
            abort(400, 'Please complete your payment.');
        }

        return view('complete_signup', [
            'register_attempt' => $register_attempt
        ]);
    }
}
