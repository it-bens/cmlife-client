<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Authentication;

use Generator;
use ITB\CmlifeClient\Authentication\AuthenticationTokens;
use ITB\CmlifeClient\Authentication\AuthenticationTokensException\AuthenticationClientErrorException;
use ITB\CmlifeClient\Authentication\AuthenticationTokensException\AuthenticationExpiredException;
use ITB\CmlifeClient\Authentication\AuthenticationTokensException\AuthenticationRequestException;
use ITB\CmlifeClient\Authentication\AuthenticationTokensException\AuthenticationServerErrorException;
use ITB\CmlifeClient\Authentication\AuthenticationTokensException\InvalidCredentialsException;
use ITB\CmlifeClient\Exception\AuthenticationException;
use LogicException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

final class AuthenticationTokensTest extends TestCase
{
    use UsernameAndPasswordToCookieValuesTrait;

    /**
     * @return Generator
     * @throws AuthenticationException
     */
    public function provideForTestAddAuthenticationHeadersToHttpClientWithExpiredAuthentication(): Generator
    {
        $credentials = $this->createCookieValuesFromUsernameAndPassword($_ENV['CMLIFE_USERNAME'], $_ENV['CMLIFE_PASSWORD']);
        $sessionIdCookie = $this->createSessionIdCookie($credentials['sessionId']);
        $xsrfTokenCookie = $this->createXsrfTokenCookie($credentials['xsrfToken']);

        $httpClient = new MockHttpClient(
            [new MockResponse('', ['http_code' => Response::HTTP_OK]), new MockResponse('', ['http_code' => Response::HTTP_UNAUTHORIZED])]
        );
        $authenticationTokens = new AuthenticationTokens($sessionIdCookie, $xsrfTokenCookie, $httpClient);

        yield [$authenticationTokens, HttpClient::create()];
    }

    /**
     * @return Generator
     * @throws AuthenticationException
     */
    public function provideForTestAddAuthenticationHeadersToHttpClientWithUnexpiredAuthentication(): Generator
    {
        $credentials = $this->createCookieValuesFromUsernameAndPassword($_ENV['CMLIFE_USERNAME'], $_ENV['CMLIFE_PASSWORD']);
        $sessionIdCookie = $this->createSessionIdCookie($credentials['sessionId']);
        $xsrfTokenCookie = $this->createXsrfTokenCookie($credentials['xsrfToken']);

        $httpClient = HttpClient::create();
        $authenticationTokens = new AuthenticationTokens($sessionIdCookie, $xsrfTokenCookie, $httpClient);

        yield [$authenticationTokens];
    }

    /**
     * @return Generator
     */
    public function provideForTestConstructWithInvalidCredentials(): Generator
    {
        $httpClient = HttpClient::create();
        $sessionIdCookie = $this->createSessionIdCookie('11111111');
        $xsrfTokenCookie = $this->createXsrfTokenCookie('11111111');

        yield [$httpClient, $sessionIdCookie, $xsrfTokenCookie];
    }

    /**
     * @return Generator
     * @throws AuthenticationException
     */
    public function provideForTestConstructWithValidCredentials(): Generator
    {
        $httpClient = HttpClient::create();
        $credentials = $this->createCookieValuesFromUsernameAndPassword($_ENV['CMLIFE_USERNAME'], $_ENV['CMLIFE_PASSWORD']);
        $sessionIdCookie = $this->createSessionIdCookie($credentials['sessionId']);
        $xsrfTokenCookie = $this->createXsrfTokenCookie($credentials['xsrfToken']);

        yield [$httpClient, $sessionIdCookie, $xsrfTokenCookie];
    }

    /**
     * @return Generator
     * @throws AuthenticationException
     */
    public function provideForTestConstructionWithMockHttpClientReturningStatusCodes(): Generator
    {
        $credentials = $this->createCookieValuesFromUsernameAndPassword($_ENV['CMLIFE_USERNAME'], $_ENV['CMLIFE_PASSWORD']);
        $sessionIdCookie = $this->createSessionIdCookie($credentials['sessionId']);
        $xsrfTokenCookie = $this->createXsrfTokenCookie($credentials['xsrfToken']);

        // transport exception
        $httpClient = new MockHttpClient(new MockResponse('', ['error' => true]));
        yield 'HTTP transport exception' => [$httpClient, $sessionIdCookie, $xsrfTokenCookie, AuthenticationRequestException::class];

        // client exceptions (400 - 500)
        $httpClient = new MockHttpClient(new MockResponse('', ['http_code' => Response::HTTP_BAD_REQUEST]));
        yield 'HTTP status: bad request' => [$httpClient, $sessionIdCookie, $xsrfTokenCookie, AuthenticationClientErrorException::class];
        $httpClient = new MockHttpClient(new MockResponse('', ['http_code' => Response::HTTP_PAYMENT_REQUIRED]));
        yield 'HTTP status: payment required' => [$httpClient, $sessionIdCookie, $xsrfTokenCookie, AuthenticationClientErrorException::class];
        $httpClient = new MockHttpClient(new MockResponse('', ['http_code' => Response::HTTP_NOT_FOUND]));
        yield 'HTTP status: not found' => [$httpClient, $sessionIdCookie, $xsrfTokenCookie, AuthenticationClientErrorException::class];
        $httpClient = new MockHttpClient(new MockResponse('', ['http_code' => Response::HTTP_METHOD_NOT_ALLOWED]));
        yield 'HTTP status: method not allowed' => [$httpClient, $sessionIdCookie, $xsrfTokenCookie, AuthenticationClientErrorException::class];
        $httpClient = new MockHttpClient(new MockResponse('', ['http_code' => Response::HTTP_NOT_ACCEPTABLE]));
        yield 'HTTP status: not acceptable' => [$httpClient, $sessionIdCookie, $xsrfTokenCookie, AuthenticationClientErrorException::class];
        $httpClient = new MockHttpClient(new MockResponse('', ['http_code' => Response::HTTP_REQUEST_TIMEOUT]));
        yield 'HTTP status: request timeout' => [$httpClient, $sessionIdCookie, $xsrfTokenCookie, AuthenticationClientErrorException::class];
        $httpClient = new MockHttpClient(new MockResponse('', ['http_code' => Response::HTTP_TOO_MANY_REQUESTS]));
        yield 'HTTP status: too many requests' => [$httpClient, $sessionIdCookie, $xsrfTokenCookie, AuthenticationClientErrorException::class];

        // server exceptions (>= 500)
        $httpClient = new MockHttpClient(new MockResponse('', ['http_code' => Response::HTTP_INTERNAL_SERVER_ERROR]));
        yield 'HTTP status: internal server error' => [$httpClient, $sessionIdCookie, $xsrfTokenCookie, AuthenticationServerErrorException::class];
        $httpClient = new MockHttpClient(new MockResponse('', ['http_code' => Response::HTTP_BAD_GATEWAY]));
        yield 'HTTP status: bad gateway' => [$httpClient, $sessionIdCookie, $xsrfTokenCookie, AuthenticationServerErrorException::class];
        $httpClient = new MockHttpClient(new MockResponse('', ['http_code' => Response::HTTP_SERVICE_UNAVAILABLE]));
        yield 'HTTP status: service unavailable' => [$httpClient, $sessionIdCookie, $xsrfTokenCookie, AuthenticationServerErrorException::class];
        $httpClient = new MockHttpClient(new MockResponse('', ['http_code' => Response::HTTP_GATEWAY_TIMEOUT]));
        yield 'HTTP status: gateway timeout' => [$httpClient, $sessionIdCookie, $xsrfTokenCookie, AuthenticationServerErrorException::class];

        // unhandled status code
        $httpClient = new MockHttpClient(new MockResponse('', ['http_code' => Response::HTTP_CONTINUE]));
        yield 'HTTP status: continue' => [$httpClient, $sessionIdCookie, $xsrfTokenCookie, LogicException::class];
    }

    /**
     * @return Generator
     * @throws AuthenticationException
     */
    public function provideForTestGetAuthenticationHeadersWithExpiredAuthentication(): Generator
    {
        $credentials = $this->createCookieValuesFromUsernameAndPassword($_ENV['CMLIFE_USERNAME'], $_ENV['CMLIFE_PASSWORD']);
        $sessionIdCookie = $this->createSessionIdCookie($credentials['sessionId']);
        $xsrfTokenCookie = $this->createXsrfTokenCookie($credentials['xsrfToken']);

        $httpClient = new MockHttpClient(
            [new MockResponse('', ['http_code' => Response::HTTP_OK]), new MockResponse('', ['http_code' => Response::HTTP_UNAUTHORIZED])]
        );
        $authenticationTokens = new AuthenticationTokens($sessionIdCookie, $xsrfTokenCookie, $httpClient);

        yield [$authenticationTokens];
    }

    /**
     * @dataProvider provideForTestAddAuthenticationHeadersToHttpClientWithExpiredAuthentication
     *
     * @param AuthenticationTokens $authenticationTokens
     * @param HttpClientInterface $httpClient
     * @return void
     * @throws AuthenticationException
     */
    public function testAddAuthenticationHeadersToHttpClientWithExpiredAuthentication(
        AuthenticationTokens $authenticationTokens,
        HttpClientInterface $httpClient
    ): void {
        $this->expectException(AuthenticationExpiredException::class);
        $authenticationTokens->addAuthenticationHeadersToHttpClient($httpClient);
    }

    /**
     * @dataProvider provideForTestAddAuthenticationHeadersToHttpClientWithUnexpiredAuthentication
     *
     * @param AuthenticationTokens $authenticationTokens
     * @return void
     * @throws AuthenticationException
     * @throws ReflectionException
     */
    public function testAddAuthenticationHeadersToHttpClientWithUnexpiredAuthentication(AuthenticationTokens $authenticationTokens): void
    {
        $httpClient = new MockHttpClient();
        $httpClient = $authenticationTokens->addAuthenticationHeadersToHttpClient($httpClient);

        $httpClientReflection = new ReflectionClass($httpClient);
        $defaultOptionsProperty = $httpClientReflection->getProperty('defaultOptions');
        $defaultOptions = $defaultOptionsProperty->getValue($httpClient);

        $this->assertArrayHasKey('normalized_headers', $defaultOptions);
        $this->assertArrayHasKey('x-xsrf-token', $defaultOptions['normalized_headers']);
        $this->assertArrayHasKey('cookie', $defaultOptions['normalized_headers']);
    }

    /**
     * @dataProvider provideForTestConstructWithInvalidCredentials
     *
     * @param HttpClientInterface $httpClient
     * @param Cookie $sessionCookie
     * @param Cookie $xsrfTokenCookie
     * @return void
     * @throws AuthenticationException
     */
    public function testConstructWithInvalidCredentials(HttpClientInterface $httpClient, Cookie $sessionCookie, Cookie $xsrfTokenCookie): void
    {
        $this->expectException(InvalidCredentialsException::class);
        new AuthenticationTokens($sessionCookie, $xsrfTokenCookie, $httpClient);
    }

    /**
     * @dataProvider provideForTestConstructWithValidCredentials
     *
     * @param HttpClientInterface $httpClient
     * @param Cookie $sessionCookie
     * @param Cookie $xsrfTokenCookie
     * @return void
     * @throws AuthenticationException
     */
    public function testConstructWithValidCredentials(HttpClientInterface $httpClient, Cookie $sessionCookie, Cookie $xsrfTokenCookie): void
    {
        $authenticationTokens = new AuthenticationTokens($sessionCookie, $xsrfTokenCookie, $httpClient);
        $this->assertInstanceOf(AuthenticationTokens::class, $authenticationTokens);
    }

    /**
     * @dataProvider provideForTestConstructionWithMockHttpClientReturningStatusCodes
     *
     * @param HttpClientInterface $httpClient
     * @param Cookie $sessionCookie
     * @param Cookie $xsrfTokenCookie
     * @param class-string<Throwable> $expectedException
     * @return void
     * @throws AuthenticationException
     */
    public function testConstructionWithMockHttpClientReturningStatusCodes(
        HttpClientInterface $httpClient,
        Cookie $sessionCookie,
        Cookie $xsrfTokenCookie,
        string $expectedException
    ): void {
        $this->expectException($expectedException);
        new AuthenticationTokens($sessionCookie, $xsrfTokenCookie, $httpClient);
    }

    /**
     * @dataProvider provideForTestGetAuthenticationHeadersWithExpiredAuthentication
     *
     * @param AuthenticationTokens $authenticationTokens
     * @return void
     * @throws AuthenticationException
     */
    public function testGetAuthenticationHeadersWithExpiredAuthentication(AuthenticationTokens $authenticationTokens): void
    {
        $this->expectException(AuthenticationExpiredException::class);
        $authenticationTokens->getAuthenticationHeaders();
    }

    /**
     * @param string $sessionId
     * @return Cookie
     */
    private function createSessionIdCookie(string $sessionId): Cookie
    {
        return new Cookie(
            AuthenticationTokens::COOKIE_KEY_SESSION,
            $sessionId,
            null,
            '/',
            'my.uni-bayreuth.de',
            true,
            true,
            samesite: 'Lax'
        );
    }

    /**
     * @param string $xsrfToken
     * @return Cookie
     */
    private function createXsrfTokenCookie(string $xsrfToken): Cookie
    {
        return new Cookie(
            AuthenticationTokens::COOKIE_KEY_XSRF_TOKEN,
            $xsrfToken,
            null,
            '/',
            'my.uni-bayreuth.de',
            true,
            true,
            samesite: 'Lax'
        );
    }
}
