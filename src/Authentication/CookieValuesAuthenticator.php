<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Authentication;

use ITB\CmlifeClient\Authentication\AuthenticatorException\MissingCredentialException;
use ITB\CmlifeClient\Authentication\CookieValuesAuthenticatorException\ForcedAuthenticationRenewalNotAllowedException;
use ITB\CmlifeClient\Exception\AuthenticationException;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class CookieValuesAuthenticator extends BaseAuthenticator
{
    public const CREDENTIAL_NAME_SESSION_ID = 'sessionId';
    public const CREDENTIAL_NAME_XSRF_TOKEN = 'xsrfToken';

    /**
     * @param HttpClientInterface $authenticationHttpClient
     */
    public function __construct(private readonly HttpClientInterface $authenticationHttpClient)
    {
    }

    /**
     * @return CookieValuesAuthenticator
     */
    public static function createWithDefaultHttpClient(): CookieValuesAuthenticator
    {
        $authenticationHttpClient = HttpClient::create();

        return new self($authenticationHttpClient);
    }

    /**
     * @param array<string, mixed> $credentials
     * @param bool $forceAuthenticationRenewal
     * @return void
     * @throws AuthenticationException
     */
    public function authenticate(array $credentials, bool $forceAuthenticationRenewal = false): void
    {
        $credentials = array_filter($credentials, static function ($key, $value): bool {
            return is_string($key) && is_string($value);
        }, ARRAY_FILTER_USE_BOTH);
        /** @var array<string, string> $credentials */

        $sessionId = $this->extractSessionIdFromCredentials($credentials);
        $xsrfToken = $this->extractXsrfTokenFromCredentials($credentials);

        if (true === $forceAuthenticationRenewal) {
            throw ForcedAuthenticationRenewalNotAllowedException::create();
        }

        if (null !== $this->tokens) {
            return;
        }

        $sessionIdCookie = new Cookie(
            AuthenticationTokens::COOKIE_KEY_SESSION,
            $sessionId,
            null,
            '/',
            'my.uni-bayreuth.de',
            true,
            true,
            samesite: 'Lax'
        );
        $xsrfTokenCookie = new Cookie(
            AuthenticationTokens::COOKIE_KEY_XSRF_TOKEN,
            $xsrfToken,
            null,
            '/',
            'my.uni-bayreuth.de',
            true,
            true,
            samesite: 'Lax'
        );

        $this->tokens = new AuthenticationTokens($sessionIdCookie, $xsrfTokenCookie, $this->authenticationHttpClient);
    }

    /**
     * @param array<string, string> $credentials
     * @return string
     */
    private function extractSessionIdFromCredentials(array $credentials): string
    {
        if (!array_key_exists(self::CREDENTIAL_NAME_SESSION_ID, $credentials)) {
            throw MissingCredentialException::create(self::CREDENTIAL_NAME_SESSION_ID);
        }

        return $credentials[self::CREDENTIAL_NAME_SESSION_ID];
    }

    /**
     * @param array<string, string> $credentials
     * @return string
     */
    private function extractXsrfTokenFromCredentials(array $credentials): string
    {
        if (!array_key_exists(self::CREDENTIAL_NAME_XSRF_TOKEN, $credentials)) {
            throw MissingCredentialException::create(self::CREDENTIAL_NAME_XSRF_TOKEN);
        }

        return $credentials[self::CREDENTIAL_NAME_XSRF_TOKEN];
    }
}
