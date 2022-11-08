<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Authentication\AuthenticationTokensException;

use ITB\CmlifeClient\Exception\AuthenticationException;
use RuntimeException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class AuthenticationRequestException extends RuntimeException implements AuthenticationException
{
    /**
     * @param string $authenticationTestUri
     * @param TransportExceptionInterface $transportException
     * @return AuthenticationRequestException
     */
    public static function create(
        string $authenticationTestUri,
        TransportExceptionInterface $transportException
    ): AuthenticationRequestException {
        return new self(
            sprintf('The request to test the authentication to "%s" failed with a transport exception.', $authenticationTestUri),
            previous: $transportException
        );
    }
}
