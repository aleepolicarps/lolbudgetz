<?php

namespace App\Http\Controllers;

use App\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_subscription = UserSubscription::where('user_id', Auth::user()->id)->first();
        return view('home', ['user_subscription' => $user_subscription]);
    }
}
