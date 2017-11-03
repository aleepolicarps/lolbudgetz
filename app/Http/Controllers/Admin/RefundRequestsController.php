<?php

namespace App\Http\Controllers\Admin;

use App\Services\RefundsHandler;
use App\RebillTransaction;
use App\SaleTransaction;
use App\RefundRequest;
use App\Enums\RefundRequestStatus;
use App\Exceptions\PaymentException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RefundRequestsController extends Controller
{
    private $refunds_handler;

    public function __construct(RefundsHandler $refunds_handler)
    {
        $this->refunds_handler = $refunds_handler;
    }

    public function show_refund_requests()
    {
        $current_user = Auth::user();
        if(!$current_user || !$current_user->is_admin()) {
            abort(401, 'You are unauthorized to access this page.');
        }
        $refund_requests = RefundRequest::orderBy('created_at', 'DESC')->get();
        return view('admin.refund_requests', [
            'refund_requests' => $refund_requests
        ]);
    }

    public function refund(Request $request)
    {
        try {
            $transaction = SaleTransaction::where('transaction_id', $request->input('transaction_id'))->first();
            if(!$transaction) {
                $transaction = RebillTransaction::where('transaction_id', $request->input('transaction_id'))->first();
            }

            if(!$transaction) {
                abort(404, 'Transaction not found');
            }

            $refund_transaction = $this->refunds_handler->refund($transaction);
            if(!$refund_transaction->is_successful()) {
                abort(400, $refund_transaction->message);
            }
            return response()->json(['status' => 'success']);
        } catch(PaymentException $e) {
            abort(400, $e->getMessage());
        } catch(Exception $e) {
            abort(500, 'Unknown error occurred.');
        }
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
