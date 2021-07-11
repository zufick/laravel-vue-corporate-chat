<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return response(['users' => User::all()]);
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email,'.$user->id,
            'approved' => 'required|boolean',
            'admin' => 'required|boolean',
        ]);
        if($validator->fails()){
            return response(['errors' => $validator->errors()])->setStatusCode(422);
        }

        $validated = $validator->validated();

        if($request->password){
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);
        return $user;
    }

    public function delete(Request  $request, User  $user)
    {
        $user->delete();
        return response(['message' => 'Пользователь удалён.']);
    }
}
