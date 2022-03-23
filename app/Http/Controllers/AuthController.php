<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponser;

    public function register(Request $request)
    {
        $attr= $request->validate([
            'name'=> 'required|string|max:255',
            'email'=> 'required|string|email|unique:users,email',
            'password'=> 'required|string|min:6|confirmed'
        ]);

        $user= User::create([
            'name'=> $attr['name'],
            'password'=> bcrypt ($attr['password']),
            'email'=> $attr['email'],
        ]);

        return $this->success([
            'token'=>$user->createToken('API Token')->plainTextToken,
            'user' => $user
        ]);
    }

    public function login (Request $request)
    {
        $attr=$request->validate([
            'email'=> 'required|string|email',
            'password'=> 'required|string|min:6'
        ]);

        if(!Auth::attempt($attr)){
            return $this->error('Credentials do not match', 401);
        }

        return $this->success([
            'token'=> $request->user()->createToken('API Token')->plainTextToken,
            'user' => $request->user()
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return[
            'message'=>'Tokens revoked'
        ];
    }

}