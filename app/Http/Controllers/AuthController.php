<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $found_user = User::where('email', $request->input('email'))->first();

        if ($found_user) {
            return response()->json([
                'message' => 'Email is already registred.'
            ], 400);
        } else {
            $user = User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]);

            $token = $user->createToken('token')->plainTextToken;

            $cookie = cookie('jwt', $token, 60 * 24); // 1 day

            return response([
                'accessToken' => $token,
                'message' => 'User registered successfully'
            ])->withCookie($cookie);
        }
        return response()->json(['email' => $found_user->email]);
    }

    public function login(Request $request)
    {
        // if (!Auth::attempt($request->only('email', 'password'))) {
        //     return response([
        //         'message' => 'Invalid credentials!'
        //     ], 401);
        // }

        // Check email
        $user = User::where('email', $request->input('email'))->first();

        if(!$user) {
            return response([
                'message' => 'Email is not yet registered.'
            ], 401);
        }

        // Check password
        if(!Hash::check($request->input('password'), $user->password)) {
            return response([
                'message' => 'Invalid password.'
            ], 401);
        }

        // $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('jwt', $token, 60 * 24); // 1 day

        return response([
            'accessToken' => $token,
            'message' => 'User logged in successfully.'
        ])->withCookie($cookie);
    }

    public function user()
    {
        return Auth::user();
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response([
            'message' => 'Success'
        ])->withCookie($cookie);
    }
}
