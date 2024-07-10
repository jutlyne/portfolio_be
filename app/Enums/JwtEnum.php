<?php

namespace App\Enum;

enum JwtEnum: string
{
    case RefreshTokenTtl = 60 * 24 * 30;
}
