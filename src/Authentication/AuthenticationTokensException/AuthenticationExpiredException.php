<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Authentication\AuthenticationTokensException;

use ITB\CmlifeClient\Exception\AuthenticationException;
use RuntimeException;

final class AuthenticationExpiredException extends RuntimeException implements AuthenticationException
{
    /**
     * @return AuthenticationExpiredException
     */
    public static function create(): AuthenticationExpiredException
    {
        return new self('The performed authentication or the provided credentials are expired.');
    }
}
