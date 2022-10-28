<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Authentication\Exception;

use ITB\CmlifeClient\Exception\AuthenticationException;
use RuntimeException;

final class MissingCookiesException extends RuntimeException implements AuthenticationException
{
    /**
     * @param string[] $missingCookieNames
     * @return MissingCookiesException
     */
    public static function create(array $missingCookieNames): MissingCookiesException
    {
        return new self(
            sprintf(
                'The cookies [%s] are missing from the authentication browser but they are required for fully authenticated requests.',
                implode(', ', $missingCookieNames)
            )
        );
    }
}
