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
     * @param array<string, mixed> $credentials
     * @param bool $forceAuthenticationRenewal
     * @return void
     * @throws AuthenticationException
     */
    public function authenticate(array $credentials, bool $forceAuthenticationRenewal = false): void;

    /**
     * @return array{'X-XSRF-TOKEN': string, 'Cookie': string}
     * @throws AuthenticationException
     */
    public function getAuthenticationHeaders(): array;
}
