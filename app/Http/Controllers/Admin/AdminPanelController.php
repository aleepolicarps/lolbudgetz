<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminPanelController extends Controller
{
    public function index()
    {
        $current_user = Auth::user();
        if(!$current_user || !$current_user->is_admin()) {
            abort(401, 'You are unauthorized to access this page.');
        }

        return view('admin.index');
    }
}
