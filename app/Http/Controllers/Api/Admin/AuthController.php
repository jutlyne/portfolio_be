<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token as JWTToken;


class AuthController extends BaseController implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['login']),
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
            $token = JWTAuth::fromUser($user);

            JWTAuth::factory()->setTTL($refeshTokenTTL);
            $refeshToken = JWTAuth::fromUser($user);

            return $this->successResponse([
                'token' => $token,
                'refeshToken' => $refeshToken
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
            JWTAuth::manager()->invalidate(new JWTToken($token), false);
        }

        return $this->successResponse([], __('auth.logout'));
    }

    public function refreshToken()
    {
        $token = JWTAuth::parseToken()->refresh();

        return $this->successResponse(['token' => $token], __('auth.success'));
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
