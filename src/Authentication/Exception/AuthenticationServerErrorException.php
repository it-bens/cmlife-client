<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Authentication\Exception;

use ITB\CmlifeClient\Exception\AuthenticationException;
use RuntimeException;

final class AuthenticationServerErrorException extends RuntimeException implements AuthenticationException
{
    /**
     * @param string $authenticationTestUri
     * @param int $statusCode
     * @return AuthenticationServerErrorException
     */
    public static function create(string $authenticationTestUri, int $statusCode): AuthenticationServerErrorException
    {
        return new self(
            sprintf(
                'The request to test the authentication to "%s" failed with a server error. The status code was %d.',
                $authenticationTestUri,
                $statusCode
            ),
        );
    }
}
