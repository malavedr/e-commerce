<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuthController
 *
 * Handles user authentication via API, including login, logout,
 * and retrieval of the authenticated user's profile.
 *
 * @package App\Http\Controllers\Api
 */
class AuthController extends Controller
{
    use HandlesApiResponses;

    /**
     * Authenticate a user and return an access token.
     *
     * Validates the user credentials, revokes any previous tokens,
     * and issues a new one along with user information.
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \App\Exceptions\InvalidCredentialsException
     */
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

    /**
     * Logout the authenticated user by revoking the current token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        return $this->success(__('auth.logout_success'));
    }

    /**
     * Return the currently authenticated user's information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        return $this->success(__('auth.profile_success'), new UserResource($request->user()));
    }
}
