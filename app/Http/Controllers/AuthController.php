<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|string|min:6'
        ]);

        if($validator->fails()){
            return response(['errors' => $validator->errors()])->setStatusCode(422);
        }

        $validated = $validator->validated();
        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);
        return response(['message' => 'Вы успешно зарегистрировались!'])->setStatusCode(201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if($validator->fails()){
            return response(['errors' => $validator->errors()])->setStatusCode(422);
        }

        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return response(['error' => 'Неверный логин или пароль!'])->setStatusCode(401);
        }

        $token = $user->createToken('vueAppToken')->plainTextToken;
        return response(['token' => $token]);
    }
}
