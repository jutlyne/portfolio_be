<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\Auth\RefeshTokenRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Models\User;
use App\Traits\JwtTrait;

class AuthController extends BaseController implements HasMiddleware
{
    use JwtTrait;

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['login', 'refreshToken']),
        ];
    }

    /**
     * Handle the user login request.
     *
     * @param LoginRequest $request The login request containing the user's credentials.
     * @return JsonResponse The JSON response containing either a JWT token or an error message.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $refeshTokenTTL = 60 * 24 * 30;

        if (Auth::attempt($credentials)) {
            $user = auth()->user();
            @list($token, $refreshToken) = $this->generateTokensWithTTL($user, $refeshTokenTTL);

            return $this->successResponse([
                'token' => $token,
                'refreshToken' => $refreshToken
            ], __('auth.success'));
        }

        return $this->failedResponse(__('auth.failed'));
    }

    /**
     * Handle logout.
     *
     * @return RedirectResponse
     */
    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        if ($token) {
            $this->invalidateToken($token);
        }

        return $this->successResponse([], __('auth.logout'));
    }

     /**
     * Refresh the JWT token using the provided refresh token.
     *
     * @param \App\Http\Requests\RefreshTokenRequest $request The request containing the refresh token.
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(RefeshTokenRequest $request)
    {
        $token = $request->refresh_token;
        $jwtClass = $this->getJwtClassFromToken($token);
        $userId = $jwtClass->getClaim('sub');
        $timestampTTL = $jwtClass->getClaim('exp');
        $diffInMinutes = $this->calculateMinutesFromNow($timestampTTL);

        if ($userId) {
            $user = User::find($userId);
            @list($newToken, $refreshToken) = $this->generateTokensWithTTL($user, $diffInMinutes);
            $this->invalidateToken($token);

            return $this->successResponse([
                'token' => $newToken,
                'refreshToken' => $refreshToken
            ], __('auth.success'));
        }

        return $this->failedResponse(__('auth.failed'));
    }

    /**
     * Display the authenticated user's profile.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing user information and a success message.
     */
    public function profile(): JsonResponse
    {
        $user = Auth::user();

        return $this->successResponse($user, __('messages.success'));
    }
}
