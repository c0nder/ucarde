<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request)
    {
        return response([
            'user' => $request->user()
        ]);
    }

    public function update(UserUpdateRequest $request)
    {
        $validated = $request->validated();

        $request->user()->update(
            [
                'name' => $validated['name'],
                'email' => $validated['email']
            ]
        );

        return response($request->user());
    }
}
