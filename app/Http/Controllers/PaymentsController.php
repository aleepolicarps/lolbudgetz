<?php

namespace App\Http\Controllers;

use App\WebId;
use App\RefundRequest;
use App\Mail\SignupSuccessful;
use App\RegisterAttempt;
use App\Enums\RefundRequestStatus;
use App\Services\SaleTransactionsLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class PaymentsController extends Controller
{
    private $sale_transactions_logger;
    private $maxpay_helper;

    public function __construct(SaleTransactionsLogger $sale_transactions_logger)
    {
        $this->sale_transactions_logger = $sale_transactions_logger;
        $this->maxpay_helper = new \Maxpay\Scriney(env('MAXPAY_PUBLIC_KEY'), env('MAXPAY_SECRET_KEY'));
    }

    public function handle_failed_payment(Request $request)
    {
        $callback_params = $request->all();
        if(!$this->maxpay_helper->validateCallback($callback_params)) {
            abort(500);
        }

        $sale_transaction = $this->sale_transactions_logger->save_from_callback($callback_params);
        return view('failed_signup');
    }

    public function handle_successful_payment(Request $request)
    {
        $callback_params = $request->all();
        if(!$this->maxpay_helper->validateCallback($callback_params)) {
            abort(500);
        }

        $sale_transaction = $this->sale_transactions_logger->save_from_callback($callback_params);
        $register_attempt = RegisterAttempt::where('uuid', $sale_transaction->uuid)->first();
        Mail::to($register_attempt->email_address)
            ->send(new SignupSuccessful($register_attempt));

        $web_id = WebId::find($sale_transaction->web_id);
        return redirect($web_id->return_url);
    }

    public function request_refund(Request $request)
    {
        $current_user = Auth::user();
        if(!$current_user) {
            abort(401, 'You are unauthorized to use this feature.');
        }

        $refund_request = new RefundRequest;
        $refund_request->user_id = $current_user->id;
        $refund_request->details = $request->input('details');
        $refund_request->save();

        return response()->json(['status' => 'success']);
    }

    public function change_refund_request_status(Request $request)
    {
        $current_user = Auth::user();
        if(!$current_user) {
            abort(401, 'You are unauthorized to use this feature.');
        }

        $request_refund = RefundRequest::find($request->input('refund_request_id'));
        if(!$request_refund) {
            abort(404, 'Refund request does not exist.');
        }

        $request_refund->status = RefundRequestStatus::from_string($request->input('status'));
        $request_refund->save();
        return response()->json([
            'status' => 'success'
        ]);
    }
}
