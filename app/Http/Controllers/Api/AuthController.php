<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try{
            if($token = Auth::attempt($credentials)){
                return response()->json([
                    'message' => 'success',
                    'token' => $this->respondWithToken($token),
                    'user' => $this->respondUserInfo(auth()->user())
                ]);
            }
            return response()->json([
                'message' => 'error',
                'error' => 'invalid credentials'
            ], 401);
        }
        catch (\Exception $exception){
            return response()->json([
                'message' => "error",
                'error' => 'unexpected error'
            ], 500);
        }
    }

    public function status()
    {
        $user = User::find(auth()->id());
        logger("user attempt".$user);
        if ($user) return response()->json([
            'message' => 'success',
            'user' => $this->respondUserInfo($user)
        ]);
        return response()->json(['message' => 'error']);
    }

    public function register(Request $request)
    {
        try {
            $user = User::create([
                'id' => $request->id,
                'name' => $request->name,
                'email' => $request->email,
                'subscription' => $request->subscription,
                'password' => Hash::make($request->password),
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'error'
            ], 409);
        }


        $token = auth()->login($user);

        return response()->json([
            'message' => 'success',
            'user' => $this->respondUserInfo($user),
            'token' => $this->respondWithToken($token)
        ]);
    }

    public function changeUserData(Request $request)
    {
        $user = $request->user();
        $userNeedsToUpdate = User::find($user->id);

        $userNeedsToUpdate->name = $request->name;
        $userNeedsToUpdate->email = $request->email;

        $userNeedsToUpdate->save();
        return response()->json([
            'message' => 'success',
            'user' => $userNeedsToUpdate
        ]);
    }

    protected function respondWithToken($token)
    {
        return ([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    protected function respondUserInfo($user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_superuser' => $user->is_superuser,
            'subscription' => $user->subscription
        ];
    }
}
