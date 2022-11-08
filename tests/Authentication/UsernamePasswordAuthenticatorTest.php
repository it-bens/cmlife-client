<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Authentication;

use Generator;
use ITB\CmlifeClient\Authentication\AuthenticationTokensException\InvalidCredentialsException;
use ITB\CmlifeClient\Authentication\AuthenticatorException\MissingCredentialException;
use ITB\CmlifeClient\Authentication\UsernamePasswordAuthenticator;
use ITB\CmlifeClient\Exception\AuthenticationException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

final class UsernamePasswordAuthenticatorTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestAuthenticateWithInvalidCredentials(): Generator
    {
        $httpClient = HttpClient::create();

        yield [
            new UsernamePasswordAuthenticator($httpClient),
            [UsernamePasswordAuthenticator::CREDENTIAL_NAME_USERNAME => 'blub', UsernamePasswordAuthenticator::CREDENTIAL_NAME_PASSWORD => '123456']
        ];
    }

    /**
     * @return Generator
     */
    public function provideForTestAuthenticateWithMissingCredential(): Generator
    {
        $httpClient = HttpClient::create();
        $authenticator = new UsernamePasswordAuthenticator($httpClient);

        yield 'username missing' => [
            $authenticator,
            [UsernamePasswordAuthenticator::CREDENTIAL_NAME_PASSWORD => $_ENV['CMLIFE_PASSWORD']],
            UsernamePasswordAuthenticator::CREDENTIAL_NAME_USERNAME
        ];
        yield 'password missing' => [
            $authenticator,
            [UsernamePasswordAuthenticator::CREDENTIAL_NAME_USERNAME => $_ENV['CMLIFE_USERNAME']],
            UsernamePasswordAuthenticator::CREDENTIAL_NAME_PASSWORD
        ];
    }

    /**
     * @return Generator
     */
    public function provideForTestAuthenticateWithValidCredentials(): Generator
    {
        $httpClient = HttpClient::create();

        yield [
            new UsernamePasswordAuthenticator($httpClient),
            [
                UsernamePasswordAuthenticator::CREDENTIAL_NAME_USERNAME => $_ENV['CMLIFE_USERNAME'],
                UsernamePasswordAuthenticator::CREDENTIAL_NAME_PASSWORD => $_ENV['CMLIFE_PASSWORD']
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
            new UsernamePasswordAuthenticator($httpClient),
            [
                UsernamePasswordAuthenticator::CREDENTIAL_NAME_USERNAME => $_ENV['CMLIFE_USERNAME'],
                UsernamePasswordAuthenticator::CREDENTIAL_NAME_PASSWORD => $_ENV['CMLIFE_PASSWORD']
            ]
        ];
    }

    /**
     * @dataProvider provideForTestAuthenticateWithInvalidCredentials
     *
     * @param UsernamePasswordAuthenticator $authenticator
     * @param array{'username': string, 'password': string} $credentials
     * @return void
     * @throws AuthenticationException
     */
    public function testAuthenticateWithInvalidCredentials(UsernamePasswordAuthenticator $authenticator, array $credentials): void
    {
        $this->expectException(InvalidCredentialsException::class);
        $authenticator->authenticate($credentials);
    }

    /**
     * @dataProvider provideForTestAuthenticateWithMissingCredential
     *
     * @param UsernamePasswordAuthenticator $authenticator
     * @param array<string, mixed> $credentials
     * @param string $expectedMissingCredentialName
     * @return void
     * @throws AuthenticationException
     */
    public function testAuthenticateWithMissingCredential(
        UsernamePasswordAuthenticator $authenticator,
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
     * @param UsernamePasswordAuthenticator $authenticator
     * @param array{'username': string, 'password': string} $credentials
     * @return void
     * @throws AuthenticationException
     */
    public function testAuthenticateWithValidCredentials(UsernamePasswordAuthenticator $authenticator, array $credentials): void
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
     * @param UsernamePasswordAuthenticator $authenticator
     * @param array{'username': string, 'password': string} $credentials
     * @return void
     * @throws AuthenticationException
     */
    public function testAuthenticationRepeatedWithForceRenewal(UsernamePasswordAuthenticator $authenticator, array $credentials): void
    {
        $authenticator->authenticate($credentials);
        $authenticationHeaders = $authenticator->getAuthenticationHeaders();

        $authenticator->authenticate($credentials, true);
        $this->assertNotEquals($authenticationHeaders['Cookie'], $authenticator->getAuthenticationHeaders()['Cookie']);
        $this->assertNotEquals($authenticationHeaders['X-XSRF-TOKEN'], $authenticator->getAuthenticationHeaders()['X-XSRF-TOKEN']);
    }

    /**
     * @dataProvider provideForTestAuthenticationRepeated
     *
     * @param UsernamePasswordAuthenticator $authenticator
     * @param array{'username': string, 'password': string} $credentials
     * @return void
     * @throws AuthenticationException
     */
    public function testAuthenticationRepeatedWithoutForceRenewal(UsernamePasswordAuthenticator $authenticator, array $credentials): void
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
        $authenticator = UsernamePasswordAuthenticator::createWithDefaultHttpClient();
        $this->assertInstanceOf(UsernamePasswordAuthenticator::class, $authenticator);
    }
}
