<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Authentication\AuthenticatorException;

use ITB\CmlifeClient\Exception\AuthenticationException;
use RuntimeException;

final class AuthenticationNotPerformedException extends RuntimeException implements AuthenticationException
{
    /**
     * @return AuthenticationNotPerformedException
     */
    public static function create(): AuthenticationNotPerformedException
    {
        return new self(
            'The Authenticator has to perform an authentication before it can be used. This can be done by calling the "authenticate" method.'
        );
    }
}
