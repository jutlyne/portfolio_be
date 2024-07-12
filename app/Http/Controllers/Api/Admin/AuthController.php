<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\Auth\RefeshTokenRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\UserProfileResource;
use App\Jobs\SendMailJob;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Traits\JwtTrait;
use App\Repositories\UserRepository;

class AuthController extends BaseController implements HasMiddleware
{
    use JwtTrait;

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['login', 'refreshToken', 'resetPassword']),
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
            $user = $this->userRepository->find($userId);
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

        return $this->successResponse(new UserProfileResource($user), __('messages.success'));
    }

    /**
     * Handle the user's password reset request.
     *
     * @param  ResetPasswordRequest  $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $user = $this->userRepository->where('email', $request->email)->first();
        $code = rand(1000, 9999);

        if ($user) {
            SendMailJob::dispatch($user, $code)->afterCommit();
        }

        return $this->successResponse([], __('messages.success'));
    }
}
