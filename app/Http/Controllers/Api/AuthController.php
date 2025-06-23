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
     * 
     * @OA\Post(
     *     path="/api/login",
     *     summary="Authenticate user and return access token",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="diego.admin.active@e-commerce.com"),
     *             @OA\Property(property="password", type="string", format="password", example="pasword")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful authentication",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully authenticated."),
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="access_token", type="string", example="9|Mh1Rr1KpFbwBtUCjQkHy3qf6KqYsHjJ9SuxENCUq7e92df75"),
     *                 @OA\Property(property="token_type", type="string", example="Bearer"),
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=6),
     *                     @OA\Property(property="name", type="string", example="Diego User Suspended"),
     *                     @OA\Property(property="email", type="string", example="diego.user.suspended@e-commerce.com"),
     *                     @OA\Property(property="phone", type="string", example="+1-240-586-8422"),
     *                     @OA\Property(property="billing_name", type="string", example="Graciela Kassulke DVM"),
     *                     @OA\Property(property="billing_tax_id", type="string", example="08934014526"),
     *                     @OA\Property(property="billing_address_line", type="string", example="521 Marilyne Mission"),
     *                     @OA\Property(property="billing_province", type="string", example="New Mexico"),
     *                     @OA\Property(property="billing_locality", type="string", example="New Antoinette"),
     *                     @OA\Property(property="billing_zipcode", type="string", example="84876"),
     *                     @OA\Property(property="status", type="string", example="suspended"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-23T13:06:36.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-23T13:06:36.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="The credentials provided are invalid.")
     * )
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
     *
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout the currently authenticated user",
     *     tags={"Auth"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful logout",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out successfully."),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="You must be authenticated to access this resource."
     *     )
     * )
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
     *
     * @OA\Get(
     *     path="/api/user",
     *     summary="Get authenticated user profile",
     *     tags={"Auth"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Authenticated user retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Authenticated user retrieved successfully."),
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Diego Admin Active"),
     *                 @OA\Property(property="email", type="string", example="diego.admin.active@e-commerce.com"),
     *                 @OA\Property(property="phone", type="string", example="(414) 705-9913"),
     *                 @OA\Property(property="billing_name", type="string", example="Willie Stoltenberg"),
     *                 @OA\Property(property="billing_tax_id", type="string", example="84821789858"),
     *                 @OA\Property(property="billing_address_line", type="string", example="672 Morar Spurs Suite 625"),
     *                 @OA\Property(property="billing_province", type="string", example="New Jersey"),
     *                 @OA\Property(property="billing_locality", type="string", example="Treutelberg"),
     *                 @OA\Property(property="billing_zipcode", type="string", example="41710-8957"),
     *                 @OA\Property(property="status", type="string", example="active"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-23T13:06:36.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-23T13:06:36.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="You must be authenticated to access this resource."
     *     )
     * )
     */
    public function user(Request $request)
    {
        return $this->success(__('auth.profile_success'), new UserResource($request->user()));
    }
}
