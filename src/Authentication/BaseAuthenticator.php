<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Authentication;

use ITB\CmlifeClient\Authentication\AuthenticatorException\AuthenticationNotPerformedException;
use ITB\CmlifeClient\Exception\AuthenticationException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class BaseAuthenticator implements AuthenticatorInterface
{
    protected ?AuthenticationTokens $tokens = null;

    /**
     * @param HttpClientInterface $client
     * @return HttpClientInterface
     * @throws AuthenticationException
     */
    public function addAuthenticationHeadersToHttpClient(HttpClientInterface $client): HttpClientInterface
    {
        if (null === $this->tokens) {
            throw AuthenticationNotPerformedException::create();
        }

        return $this->tokens->addAuthenticationHeadersToHttpClient($client);
    }

    /**
     * @return array<string, string>
     * @throws AuthenticationException
     */
    public function getAuthenticationHeaders(): array
    {
        if (null === $this->tokens) {
            throw AuthenticationNotPerformedException::create();
        }

        return $this->tokens->getAuthenticationHeaders();
    }
}
