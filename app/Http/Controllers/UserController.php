<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Hash;
use Auth;

class UserController extends Controller
{
    //

    public function index(Request $request)
    {
        return $request->user();
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($request->only('email', 'password'))){
            return response()->json(Auth::user(), 200);
        }
        throw ValidationException::withMessages([
            'email' =>['The provided credentials are incorect.']
        ]);
    }
    
    public function logout()
    {
        Auth::logout();
    }
}
