<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Authentication\Exception;

use ITB\CmlifeClient\Exception\AuthenticationException;
use RuntimeException;

final class InvalidCredentialsException extends RuntimeException implements AuthenticationException
{
    /**
     * @return InvalidCredentialsException
     */
    public static function create(): InvalidCredentialsException
    {
        return new self('The provided credentials are invalid or they do not authorize for cmlife access.');
    }
}
