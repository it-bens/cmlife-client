<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Authentication;

use ITB\CmlifeClient\Authentication\Exception\AuthenticationClientErrorException;
use ITB\CmlifeClient\Authentication\Exception\AuthenticationExpiredException;
use ITB\CmlifeClient\Authentication\Exception\AuthenticationRequestException;
use ITB\CmlifeClient\Authentication\Exception\AuthenticationServerErrorException;
use ITB\CmlifeClient\Authentication\Exception\InvalidCredentialsException;
use ITB\CmlifeClient\Authentication\Exception\MissingCookiesException;
use ITB\CmlifeClient\Exception\AuthenticationException;
use LogicException;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class AuthenticationTokens
{
    private const URI_FOR_AUTHENTICATION_CHECK = 'https://my.uni-bayreuth.de/v3/cmco/api/courses/searches/minimal';

    private const COOKIE_KEY_SESSION = 'SESSION';
    private const COOKIE_KEY_XSRF_TOKEN = 'XSRF-TOKEN';

    private readonly CookieJar $cookieJar;

    /**
     * @param Cookie $sessionCookie
     * @param Cookie $xsrfTokenCookie
     * @param HttpClientInterface $httpClient
     * @throws AuthenticationException
     */
    public function __construct(Cookie $sessionCookie, private readonly Cookie $xsrfTokenCookie, private readonly HttpClientInterface $httpClient)
    {
        $this->cookieJar = new CookieJar();
        $this->cookieJar->set($sessionCookie);
        $this->cookieJar->set($xsrfTokenCookie);

        if (false === $this->isValid()) {
            throw InvalidCredentialsException::create();
        }
    }

    /**
     * @param HttpBrowser $browser
     * @param HttpClientInterface $client
     * @return AuthenticationTokens
     * @throws AuthenticationException
     */
    public static function fromAuthenticationBrowser(HttpBrowser $browser, HttpClientInterface $client): AuthenticationTokens
    {
        $missingCookies = [];

        $session = $browser->getCookieJar()->get(self::COOKIE_KEY_SESSION, domain: 'my.uni-bayreuth.de');
        if (null === $session) {
            $missingCookies[] = self::COOKIE_KEY_SESSION;
        }
        /** @var Cookie $session */

        $xsrfToken = $browser->getCookieJar()->get(self::COOKIE_KEY_XSRF_TOKEN, domain: 'my.uni-bayreuth.de');
        if (null === $xsrfToken) {
            $missingCookies[] = self::COOKIE_KEY_XSRF_TOKEN;
        }
        /** @var Cookie $xsrfToken */

        if (0 !== count($missingCookies)) {
            throw MissingCookiesException::create($missingCookies);
        }

        return new self($session, $xsrfToken, $client);
    }

    /**
     * @param HttpClientInterface $client
     * @return HttpClientInterface
     * @throws AuthenticationException
     */
    public function addAuthenticationHeadersToHttpClient(HttpClientInterface $client): HttpClientInterface
    {
        // Because the authentication is checked in the constructor, the credentials have to be expired if the became invalid since the tokens construction.
        if (false === $this->isValid()) {
            throw AuthenticationExpiredException::create();
        }

        return $client->withOptions(['headers' => ['X-XSRF-TOKEN' => $this->xsrfTokenCookie->getRawValue(), 'Cookie' => $this->toCookieHeader()]]);
    }

    /**
     * @return array<string, string>
     * @throws AuthenticationException
     */
    public function getAuthenticationHeaders(): array
    {
        // Because the authentication is checked in the constructor, the credentials have to be expired if the became invalid since the tokens construction.
        if (false === $this->isValid()) {
            throw AuthenticationExpiredException::create();
        }

        return ['X-XSRF-TOKEN' => $this->xsrfTokenCookie->getRawValue(), 'Cookie' => $this->toCookieHeader()];
    }

    /**
     * @return bool
     * @throws AuthenticationException
     */
    private function isValid(): bool
    {
        try {
            $response = $this->httpClient->request(
                'POST',
                self::URI_FOR_AUTHENTICATION_CHECK,
                [
                    'max_redirects' => 0,
                    'headers' => [
                        'X-XSRF-TOKEN' => $this->xsrfTokenCookie->getRawValue(),
                        'Cookie' => $this->toCookieHeader(),
                        'Content-Type' => 'application/json'
                    ],
                    'json' => [0]
                ]
            );
            $statusCode = $response->getStatusCode();
        } catch (TransportExceptionInterface $exception) {
            throw AuthenticationRequestException::create(self::URI_FOR_AUTHENTICATION_CHECK, $exception);
        }

        // Any redirects are handled like an 'Unauthorized' status code because the client will not follow them.
        // Redirects are in this case used to force opening a login page.
        // The follow-request to the login page would result in an 'Ok' status, although the credentials were invalid.
        if (
            in_array(
                $statusCode,
                [
                Response::HTTP_UNAUTHORIZED,
                Response::HTTP_FORBIDDEN,
                Response::HTTP_MOVED_PERMANENTLY,
                Response::HTTP_FOUND,
                Response::HTTP_TEMPORARY_REDIRECT,
                Response::HTTP_PERMANENTLY_REDIRECT
                ]
            )
        ) {
            return false;
        }

        if ($statusCode >= 400 && $statusCode < 500) {
            throw AuthenticationClientErrorException::create(self::URI_FOR_AUTHENTICATION_CHECK, $statusCode);
        }

        if ($statusCode >= 500) {
            throw AuthenticationServerErrorException::create(self::URI_FOR_AUTHENTICATION_CHECK, $statusCode);
        }

        if (Response::HTTP_OK === $statusCode) {
            return true;
        }

        throw new LogicException(sprintf('The request returned the unhandled status code %d. This should never happen.', $statusCode));
    }

    /**
     * @return string
     */
    private function toCookieHeader(): string
    {
        return implode('; ', $this->cookieJar->all());
    }
}
