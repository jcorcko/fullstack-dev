<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);    

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $validated = $validator->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $input['name'] = $user->name;
        $input['email'] = $user->email;
        $input['token'] = $user->createToken('App')->plainTextToken;
        
        return response()->json($input);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['errors' => ['Invalid login details']]);
        }

        $user = Auth::user();
        $input['name'] = $user->name;
        $input['email'] = $user->email;
        $input['token'] = $user->createToken('App')->plainTextToken;

        return response()->json($input);
    }

    public function profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $validated = $validator->validated();

        $user = Auth::user();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if ($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $input['name'] = $user->name;
        $input['email'] = $user->email;

        return response()->json($input);
    }
}
