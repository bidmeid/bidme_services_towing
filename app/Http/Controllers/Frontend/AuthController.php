<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function signup()
    {
        return view('frontend.auth.signup');
    }

    public function login()
    {
        return view('frontend.auth.sigin');
    }
}
