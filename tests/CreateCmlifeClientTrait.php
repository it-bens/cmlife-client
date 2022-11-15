<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests;

use ITB\CmlifeClient\Authentication\CookieValuesAuthenticator;
use ITB\CmlifeClient\Authentication\UsernamePasswordAuthenticator;
use ITB\CmlifeClient\CmlifeClient;
use ITB\CmlifeClient\Connection\DataClient;
use ITB\CmlifeClient\Exception\AuthenticationException;
use ITB\CmlifeClient\Storage\DataManagerInterface;
use ITB\CmlifeClient\Storage\DataRepositoryInterface;

trait CreateCmlifeClientTrait
{
    use CreateDoctrineDataStorageTrait;

    /**
     * @param array{'sessionId': string, 'xsrfToken': string} $credentials
     * @param DataManagerInterface $dataManager
     * @param DataRepositoryInterface $dataRepository
     * @return CmlifeClient
     * @throws AuthenticationException
     */
    private static function createCmlifeClientWithCookieValuesAuthentication(
        array $credentials,
        DataManagerInterface $dataManager,
        DataRepositoryInterface $dataRepository
    ): CmlifeClient {
        $authenticator = CookieValuesAuthenticator::createWithDefaultHttpClient();
        $authenticator->authenticate($credentials);
        $dataClient = DataClient::createWithDefaultHttpClient($authenticator);

        return new CmlifeClient($dataClient, $dataManager, $dataRepository);
    }

    /**
     * @param array{'username': string, 'password': string} $credentials
     * @param DataManagerInterface $dataManager
     * @param DataRepositoryInterface $dataRepository
     * @return CmlifeClient
     * @throws AuthenticationException
     */
    private static function createCmlifeClientWithUsernameAndPasswordAuthentication(
        array $credentials,
        DataManagerInterface $dataManager,
        DataRepositoryInterface $dataRepository
    ): CmlifeClient {
        $authenticator = UsernamePasswordAuthenticator::createWithDefaultHttpClient();
        $authenticator->authenticate($credentials);
        $dataClient = DataClient::createWithDefaultHttpClient($authenticator);

        return new CmlifeClient($dataClient, $dataManager, $dataRepository);
    }
}
