<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Authentication;

use ITB\CmlifeClient\Exception\AuthenticationException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

interface AuthenticatorInterface
{
    /**
     * @param HttpClientInterface $client
     * @return HttpClientInterface
     * @throws AuthenticationException
     */
    public function addAuthenticationHeadersToHttpClient(HttpClientInterface $client): HttpClientInterface;

    /**
     * @param string $username
     * @param string $password
     * @param bool $forceAuthentication
     * @return void
     * @throws AuthenticationException
     */
    public function authenticate(string $username, string $password, bool $forceAuthentication = false): void;

    /**
     * @return array<string, string>
     * @throws AuthenticationException
     */
    public function getAuthenticationHeaders(): array;
}
