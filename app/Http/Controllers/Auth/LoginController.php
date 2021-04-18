<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Models\User;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function authenticate(AuthLoginRequest $request)
    {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        if (Hash::check($credentials['password'], $user->password)) {
            return response([
                'api_token' => $user->api_token
            ]);
        }

        return response([
            'status' => 'error',
            'message' => 'User with these email and password not found'
        ]);
    }
}
