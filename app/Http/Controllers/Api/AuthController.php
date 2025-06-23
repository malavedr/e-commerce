<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Exceptions\InvalidCredentialsException;
use App\Traits\HandlesApiResponses;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HandlesApiResponses;

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->validated())) {
            throw new InvalidCredentialsException();
        }

        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('api-token')->plainTextToken;

        return $this->success(__('auth.login_success'), [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        return $this->success(__('auth.logout_success'));
    }

    public function me(Request $request)
    {
        return $this->success(__('auth.profile_success'), new UserResource($request->user()));
    }
}