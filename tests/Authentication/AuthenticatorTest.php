<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Authentication;

use Generator;
use ITB\CmlifeClient\Authentication\Authenticator;
use ITB\CmlifeClient\Authentication\Exception\InvalidCredentialsException;
use ITB\CmlifeClient\Exception\AuthenticationException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\HttpClient\HttpClient;

final class AuthenticatorTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideAuthenticator(): Generator
    {
        $httpClient = HttpClient::create();

        yield [new Authenticator($httpClient)];
    }

    /**
     * @dataProvider provideAuthenticator
     *
     * @param Authenticator $authenticator
     * @return void
     * @throws AuthenticationException
     */
    public function testAuthenticateWithInvalidCredentials(Authenticator $authenticator): void
    {
        $this->expectException(InvalidCredentialsException::class);
        $authenticator->authenticate('blub', '123456');
    }

    /**
     * @dataProvider provideAuthenticator
     *
     * @param Authenticator $authenticator
     * @return void
     * @throws AuthenticationException
     */
    public function testAuthenticateWithValidCredentials(Authenticator $authenticator): void
    {
        $username = $_ENV['CMLIFE_USERNAME'];
        $password = $_ENV['CMLIFE_PASSWORD'];
        $authenticator->authenticate($username, $password);

        // Testing private methods is bad practise and should be refactored.
        $authenticatorReflection = new ReflectionClass($authenticator);
        $authenticationTokensReflection = $authenticatorReflection->getProperty('tokens');

        $this->assertNotNull($authenticationTokensReflection->getValue($authenticator));
    }
}
