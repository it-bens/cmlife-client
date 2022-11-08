<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Authentication\AuthenticatorException;

use ITB\CmlifeClient\Exception\AuthenticationException;
use RuntimeException;

final class MissingCredentialException extends RuntimeException implements AuthenticationException
{
    /**
     * @param string $missingCredentialName
     */
    public function __construct(public readonly string $missingCredentialName)
    {
        $message = sprintf('The credential %s is missing from the passed credentials for authentication.', $this->missingCredentialName);

        parent::__construct($message);
    }

    /**
     * @param string $missingCredentialName
     * @return MissingCredentialException
     */
    public static function create(string $missingCredentialName): MissingCredentialException
    {
        return new self($missingCredentialName);
    }
}
