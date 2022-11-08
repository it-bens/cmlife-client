<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Authentication;

use ITB\CmlifeClient\Authentication\AuthenticatorException\MissingCredentialException;
use ITB\CmlifeClient\Authentication\UsernamePasswordAuthenticatorException\MissingCookieException;
use ITB\CmlifeClient\Exception\AuthenticationException;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class UsernamePasswordAuthenticator extends BaseAuthenticator
{
    public const CREDENTIAL_NAME_USERNAME = 'username';
    public const CREDENTIAL_NAME_PASSWORD = 'password';

    // The CSRF cookie is not set at this point because this is done via resource requests. (WTF!?)
    private const URI_FOR_XSRF_TOKEN = 'https://my.uni-bayreuth.de/custom/theme/variables.css';
    private const URI_FOR_SESSION_TOKEN = 'https://my.uni-bayreuth.de/authenticate?redirect_uri=https://my.uni-bayreuth.de/cmlife/welcome/sub-organizations';

    private readonly HttpBrowser $authenticationBrowser;

    /**
     * @param HttpClientInterface $authenticationHttpClient
     */
    public function __construct(private readonly HttpClientInterface $authenticationHttpClient)
    {
        $this->authenticationBrowser = new HttpBrowser($authenticationHttpClient);
    }

    /**
     * @return UsernamePasswordAuthenticator
     */
    public static function createWithDefaultHttpClient(): UsernamePasswordAuthenticator
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
        $username = $this->extractUsernameFromCredentials($credentials);
        $password = $this->extractPasswordFromCredentials($credentials);

        if (false === $forceAuthenticationRenewal && null !== $this->tokens) {
            return;
        }

        $this->authenticationBrowser->restart();
        $this->authenticationBrowser->request('GET', self::URI_FOR_XSRF_TOKEN);

        $domCrawler = $this->authenticationBrowser->request('GET', self::URI_FOR_SESSION_TOKEN);
        $loginButton = $domCrawler->selectButton('login');
        $loginForm = $loginButton->form();
        $loginForm['username'] = $username;
        $loginForm['password'] = $password;
        $this->authenticationBrowser->submit($loginForm)->html();

        $sessionIdCookie = $this->extractSessionIdCookieFromJar($this->authenticationBrowser->getCookieJar());
        $xsrfTokenCookie = $this->extractXsrfTokenCookieFromJar($this->authenticationBrowser->getCookieJar());

        $this->tokens = new AuthenticationTokens($sessionIdCookie, $xsrfTokenCookie, $this->authenticationHttpClient);
    }

    /**
     * @param array<string, mixed> $credentials
     * @return string
     */
    private function extractPasswordFromCredentials(array $credentials): string
    {
        if (!array_key_exists(self::CREDENTIAL_NAME_PASSWORD, $credentials)) {
            throw MissingCredentialException::create(self::CREDENTIAL_NAME_PASSWORD);
        }

        return $credentials[self::CREDENTIAL_NAME_PASSWORD];
    }

    /**
     * @param CookieJar $cookieJar
     * @return Cookie
     */
    private function extractSessionIdCookieFromJar(CookieJar $cookieJar): Cookie
    {
        $sessionIdCookie = $cookieJar->get(AuthenticationTokens::COOKIE_KEY_SESSION, domain: 'my.uni-bayreuth.de');
        if (null === $sessionIdCookie) {
            throw MissingCookieException::create(AuthenticationTokens::COOKIE_KEY_SESSION);
        }

        return $sessionIdCookie;
    }

    /**
     * @param array<string, mixed> $credentials
     * @return string
     */
    private function extractUsernameFromCredentials(array $credentials): string
    {
        if (!array_key_exists(self::CREDENTIAL_NAME_USERNAME, $credentials)) {
            throw MissingCredentialException::create(self::CREDENTIAL_NAME_USERNAME);
        }

        return $credentials[self::CREDENTIAL_NAME_USERNAME];
    }

    /**
     * @param CookieJar $cookieJar
     * @return Cookie
     */
    private function extractXsrfTokenCookieFromJar(CookieJar $cookieJar): Cookie
    {
        $xsrfTokenCookie = $cookieJar->get(AuthenticationTokens::COOKIE_KEY_XSRF_TOKEN, domain: 'my.uni-bayreuth.de');
        if (null === $xsrfTokenCookie) {
            throw MissingCookieException::create(AuthenticationTokens::COOKIE_KEY_XSRF_TOKEN);
        }

        return $xsrfTokenCookie;
    }
}
