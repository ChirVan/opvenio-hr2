<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    /**
     * Show two-factor authentication information
     */
    public function show()
    {
        $user = Auth::user();
        
        return view('auth.two-factor-info', [
            'user' => $user,
            'enabled' => !is_null($user->two_factor_secret),
            'confirmed' => !is_null($user->two_factor_confirmed_at),
        ]);
    }
}