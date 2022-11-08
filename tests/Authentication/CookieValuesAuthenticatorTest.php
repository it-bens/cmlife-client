<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Authentication;

use Generator;
use ITB\CmlifeClient\Authentication\AuthenticatorException\MissingCredentialException;
use ITB\CmlifeClient\Authentication\CookieValuesAuthenticator;
use ITB\CmlifeClient\Authentication\CookieValuesAuthenticatorException\ForcedAuthenticationRenewalNotAllowedException;
use ITB\CmlifeClient\Exception\AuthenticationException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

final class CookieValuesAuthenticatorTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestAuthenticateWithMissingCredential(): Generator
    {
        $httpClient = HttpClient::create();
        $authenticator = new CookieValuesAuthenticator($httpClient);

        yield 'session id missing' => [
            $authenticator,
            [CookieValuesAuthenticator::CREDENTIAL_NAME_XSRF_TOKEN => $_ENV['CMLIFE_XSRF_TOKEN']],
            CookieValuesAuthenticator::CREDENTIAL_NAME_SESSION_ID
        ];
        yield 'xsrf token missing' => [
            $authenticator,
            [CookieValuesAuthenticator::CREDENTIAL_NAME_SESSION_ID => $_ENV['CMLIFE_SESSION_ID']],
            CookieValuesAuthenticator::CREDENTIAL_NAME_XSRF_TOKEN
        ];
    }

    /**
     * @return Generator
     */
    public function provideForTestAuthenticateWithValidCredentials(): Generator
    {
        $httpClient = HttpClient::create();

        yield [
            new CookieValuesAuthenticator($httpClient),
            [
                CookieValuesAuthenticator::CREDENTIAL_NAME_SESSION_ID => $_ENV['CMLIFE_SESSION_ID'],
                CookieValuesAuthenticator::CREDENTIAL_NAME_XSRF_TOKEN => $_ENV['CMLIFE_XSRF_TOKEN']
            ]
        ];
    }

    /**
     * @return Generator
     */
    public function provideForTestAuthenticationRepeated(): Generator
    {
        $httpClient = HttpClient::create();

        yield [
            new CookieValuesAuthenticator($httpClient),
            [
                CookieValuesAuthenticator::CREDENTIAL_NAME_SESSION_ID => $_ENV['CMLIFE_SESSION_ID'],
                CookieValuesAuthenticator::CREDENTIAL_NAME_XSRF_TOKEN => $_ENV['CMLIFE_XSRF_TOKEN']
            ]
        ];
    }

    /**
     * @dataProvider provideForTestAuthenticateWithMissingCredential
     *
     * @param CookieValuesAuthenticator $authenticator
     * @param array<string, mixed> $credentials
     * @param string $expectedMissingCredentialName
     * @return void
     * @throws AuthenticationException
     */
    public function testAuthenticateWithMissingCredential(
        CookieValuesAuthenticator $authenticator,
        array $credentials,
        string $expectedMissingCredentialName
    ): void {
        $this->expectException(MissingCredentialException::class);
        $authenticator->authenticate($credentials);

        /** @var MissingCredentialException $exception */
        $exception = $this->getExpectedException();
        $this->assertEquals($expectedMissingCredentialName, $exception->missingCredentialName);
    }

    /**
     * @dataProvider provideForTestAuthenticateWithValidCredentials
     *
     * @param CookieValuesAuthenticator $authenticator
     * @param array<string, mixed> $credentials
     * @return void
     * @throws AuthenticationException
     */
    public function testAuthenticateWithValidCredentials(CookieValuesAuthenticator $authenticator, array $credentials): void
    {
        $authenticator->authenticate($credentials);

        $authenticationHeaders = $authenticator->getAuthenticationHeaders();
        $this->assertArrayHasKey('Cookie', $authenticationHeaders);
        $this->assertMatchesRegularExpression('/[0-9a-f]{8}-(?:[0-9a-f]{4}-){3}[0-9a-f]{12}/', $authenticationHeaders['X-XSRF-TOKEN']);
        $this->assertArrayHasKey('X-XSRF-TOKEN', $authenticationHeaders);
        $this->assertStringContainsString('SESSION', $authenticationHeaders['Cookie']);
        $this->assertStringContainsString('XSRF-TOKEN', $authenticationHeaders['Cookie']);
    }

    /**
     * @dataProvider provideForTestAuthenticationRepeated
     *
     * @param CookieValuesAuthenticator $authenticator
     * @param array{'sessionId': string, 'xsrfToken': string} $credentials
     * @return void
     * @throws AuthenticationException
     */
    public function testAuthenticationRepeatedWithForceRenewal(CookieValuesAuthenticator $authenticator, array $credentials): void
    {
        $authenticator->authenticate($credentials);

        $this->expectException(ForcedAuthenticationRenewalNotAllowedException::class);
        $authenticator->authenticate($credentials, true);
    }

    /**
     * @dataProvider provideForTestAuthenticationRepeated
     *
     * @param CookieValuesAuthenticator $authenticator
     * @param array{'sessionId': string, 'xsrfToken': string} $credentials
     * @return void
     * @throws AuthenticationException
     */
    public function testAuthenticationRepeatedWithoutForceRenewal(CookieValuesAuthenticator $authenticator, array $credentials): void
    {
        $authenticator->authenticate($credentials);
        $authenticationHeaders = $authenticator->getAuthenticationHeaders();

        $authenticator->authenticate($credentials, false);
        $this->assertEquals($authenticationHeaders['Cookie'], $authenticator->getAuthenticationHeaders()['Cookie']);
        $this->assertEquals($authenticationHeaders['X-XSRF-TOKEN'], $authenticator->getAuthenticationHeaders()['X-XSRF-TOKEN']);
    }

    /**
     * @return void
     */
    public function testCreateWithDefaultHttpClient(): void
    {
        $authenticator = CookieValuesAuthenticator::createWithDefaultHttpClient();
        $this->assertInstanceOf(CookieValuesAuthenticator::class, $authenticator);
    }
}
