<?php

namespace App\Traits;

use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\Token as JWTToken;

trait JwtTrait
{
    /**
     * Get the JWT class instance from a given token.
     *
     * @param string $token The JWT token.
     * @return \Tymon\JWTAuth\JWT The JWT class instance with the token set.
     */
    public function getJwtClassFromToken($token)
    {
        return app(JWT::class)->setToken($token);
    }

    /**
     * Generate JWT token and refresh token for a given user with a specified time-to-live (TTL).
     *
     * @param \App\Models\User $user The user for whom the tokens are generated.
     * @param int $ttl The time-to-live for the token in minutes.
     * @return array An array containing the JWT token and the refresh token.
     */
    public function generateTokensWithTTL($user, $ttl)
    {
        $token = JWTAuth::fromUser($user);
        JWTAuth::factory()->setTTL($ttl);

        $refreshToken = JWTAuth::fromUser($user);

        return [
            $token,
            $refreshToken
        ];
    }

    /**
     * Calculate the number of minutes from the current time to a given timestamp.
     *
     * @param int $timestamp The timestamp to compare against the current time.
     * @return int The number of minutes from the current time to the given timestamp.
     */
    public function calculateMinutesFromNow($timestamp)
    {
        $currentTime = Carbon::now();
        $futureTime = Carbon::createFromTimestamp($timestamp);

        return $currentTime->diffInMinutes($futureTime, false);
    }

    /**
     * Invalidate a given JWT token.
     *
     * @param string $token The JWT token to invalidate.
     * @return void
     */
    public function invalidateToken($token)
    {
        JWTAuth::manager()->invalidate(new JWTToken($token), false);
    }
}
