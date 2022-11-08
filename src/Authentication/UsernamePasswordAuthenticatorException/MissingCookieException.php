<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Authentication\UsernamePasswordAuthenticatorException;

use ITB\CmlifeClient\Exception\AuthenticationException;
use RuntimeException;

final class MissingCookieException extends RuntimeException implements AuthenticationException
{
    /**
     * @param string $missingCookieName
     * @return MissingCookieException
     */
    public static function create(string $missingCookieName): MissingCookieException
    {
        return new self(
            sprintf(
                'The cookie %s is missing from the authentication browser but it is required for fully authenticated requests.',
                $missingCookieName
            )
        );
    }
}
