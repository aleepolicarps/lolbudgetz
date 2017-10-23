<?php

namespace App\Http\Controllers;

use App\WebId;
use Illuminate\Http\Request;

class WebIdController extends Controller
{
    public function get($public_id)
    {
        $web_id = WebId::where('public_id', $public_id)->first();
        if(!$web_id) {
            abort(404);
        }

        return response()->json([
            'public_key' => env('MAXPAY_PUBLIC_KEY'),
            'product_id' => $web_id->trial_product_id,
            'locale' => $web_id->locale
         ]);
    }
}
