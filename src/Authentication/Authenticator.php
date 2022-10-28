<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Authentication;

use ITB\CmlifeClient\Authentication\Exception\AuthenticationNotPerformedException;
use ITB\CmlifeClient\Exception\AuthenticationException;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Authenticator implements AuthenticatorInterface
{
    // The CSRF cookie is not set at this point because this is done via resource requests. (WTF!?)
    private const URI_FOR_XSRF_TOKEN = 'https://my.uni-bayreuth.de/custom/theme/variables.css';
    private const URI_FOR_SESSION_TOKEN = 'https://my.uni-bayreuth.de/authenticate?redirect_uri=https://my.uni-bayreuth.de/cmlife/welcome/sub-organizations';

    private ?AuthenticationTokens $tokens = null;

    private readonly HttpBrowser $authenticationBrowser;

    /**
     * @param HttpClientInterface $authenticationHttpClient
     */
    public function __construct(private readonly HttpClientInterface $authenticationHttpClient)
    {
        $this->authenticationBrowser = new HttpBrowser($authenticationHttpClient);
    }

    /**
     * @return Authenticator
     */
    public static function createWithDefaultHttpClient(): Authenticator
    {
        $authenticationHttpClient = HttpClient::create();

        return new self($authenticationHttpClient);
    }

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
     * @param string $username
     * @param string $password
     * @param bool $forceAuthentication
     * @return void
     * @throws AuthenticationException
     */
    public function authenticate(string $username, string $password, bool $forceAuthentication = false): void
    {
        if (false === $forceAuthentication && null !== $this->tokens) {
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

        $this->tokens = AuthenticationTokens::fromAuthenticationBrowser($this->authenticationBrowser, $this->authenticationHttpClient);
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
