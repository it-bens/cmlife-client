<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Authentication;

use ITB\CmlifeClient\Authentication\CookieValuesAuthenticator;
use ITB\CmlifeClient\Authentication\UsernamePasswordAuthenticator;
use ITB\CmlifeClient\Exception\AuthenticationException;
use Symfony\Component\HttpClient\HttpClient;

trait UsernameAndPasswordToCookieValuesTrait
{
    /**
     * @param string $username
     * @param string $password
     * @return array{'sessionId': string, 'xsrfToken': string}
     * @throws AuthenticationException
     */
    private function createCookieValuesFromUsernameAndPassword(string $username, string $password): array
    {
        $httpClient = HttpClient::create();

        $usernamePasswordAuthenticator = new UsernamePasswordAuthenticator($httpClient);
        $usernamePasswordAuthenticator->authenticate([
            UsernamePasswordAuthenticator::CREDENTIAL_NAME_USERNAME => $username,
            UsernamePasswordAuthenticator::CREDENTIAL_NAME_PASSWORD => $password
        ]);
        $authenticationHeaders = $usernamePasswordAuthenticator->getAuthenticationHeaders();
        $authenticationCookies = explode('; ', $authenticationHeaders['Cookie']);
        $sessionIdCookie = array_values(
            array_filter($authenticationCookies, static function (string $cookie): bool {
                return str_starts_with($cookie, 'SESSION');
            })
        )[0];
        $sessionId = explode('=', $sessionIdCookie)[1];
        $xsrfTokenCookie = array_values(
            array_filter($authenticationCookies, static function (string $cookie): bool {
                return str_starts_with($cookie, 'XSRF-TOKEN');
            })
        )[0];
        $xsrfToken = explode('=', $xsrfTokenCookie)[1];

        return [
            CookieValuesAuthenticator::CREDENTIAL_NAME_SESSION_ID => $sessionId,
            CookieValuesAuthenticator::CREDENTIAL_NAME_XSRF_TOKEN => $xsrfToken
        ];
    }
}
