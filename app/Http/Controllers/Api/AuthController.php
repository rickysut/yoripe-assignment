<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        
    
        $user = new user;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->password = Hash::make($request->password);
        $user->save();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Register success',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function login(Request $request)
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Login success',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
            ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout success'
        ]);
    }

    public function list()
    {
        $user = Auth::user();
        if ($user){
            if ($user->role == 'admin'){
                return  response()->json([
                    'message' => 'Users list',
                    'data' => User::all()],
                    200
                ); 
            } else return response()->json([
                'message' => 'Not allow to list users'
            ], 401);

        } else 
        return response()->json([
            'message' => 'Unauthorized'
        ], 401);
    }

    public function update(Request $request,  $userId)
    {
        $user = Auth::user();
        if ($user){
            if ($user->role == 'admin'){
                $muser = User::find($userId) ;
                if ($muser) {
                    $validator = Validator::make($request->all(), [
                        'name' => 'required|string|max:255',
                        'email' => 'required|string|max:255', // not unique prevent fail update
                        'password' => 'required|string|min:8',
                        'role' => 'required|integer',
                    ]);
                    if ($validator->fails()) {
                        return response()->json($validator->errors());
                    }
                    
                    $muser->name = $request->name;
                    $muser->email = $request->email;
                    $muser->role = $request->role;
                    $muser->password = Hash::make($request->password);
                    $muser->update();

                    return response()->json([
                        'message' => 'User updated',
                        'data' => $muser->load([])->where('id', $muser->id)->get()], 
                        200
                    );
                    
                } else {
                    return response()->json([
                        'message' => 'User not found'
                    ], 404);
                }
            } else return response()->json([
                'message' => 'Not allow to update user'
            ], 401);
        } else return response()->json([
                        'message' => 'Unauthorized'
                    ], 401);
        
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        if ($user){
            if ($user->role == 'admin'){
                
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|max:255|unique:users', 
                    'password' => 'required|string|min:8',
                    'role' => 'required|integer',
                ]);
                if ($validator->fails()) {
                    return response()->json($validator->errors());
                }
                
                $user = new user;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->role = $request->role;
                $user->password = Hash::make($request->password);
                $user->save();
                return response()->json([
                    'message' => 'Create user success',
                    'data' => $user,
                ]);
                
            } else return response()->json([
                'message' => 'Not allow to create user'
            ], 401);
        } else return response()->json([
                        'message' => 'Unauthorized'
                    ], 401);
        
    }

    public function show($userId)
    {
        $user = Auth::user();
        if ($user){
            if ($user->role == 'admin'){
                return  response()->json([
                    'message' => 'Get User',
                    'data' => User::where('id', '=', $userId)->get() ],
                    200
                ); 
            } else return response()->json([
                'message' => 'Not allow to get user'
            ], 401);

        } else 
        return response()->json([
            'message' => 'Unauthorized'
        ], 401);
    }
}
