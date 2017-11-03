<?php

namespace App\Http\Controllers\Admin;

use App\RefundRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RefundRequestsController extends Controller
{
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
}
