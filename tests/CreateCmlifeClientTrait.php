<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests;

use ITB\CmlifeClient\Authentication\CookieValuesAuthenticator;
use ITB\CmlifeClient\Authentication\UsernamePasswordAuthenticator;
use ITB\CmlifeClient\CmlifeClient;
use ITB\CmlifeClient\Connection\DataClient;
use ITB\CmlifeClient\Exception\AuthenticationException;
use ITB\CmlifeClient\Exception\StorageException;
use ITB\CmlifeClient\Storage\DataStorage;

trait CreateCmlifeClientTrait
{
    /**
     * @param array{'sessionId': string, 'xsrfToken': string} $credentials
     * @return CmlifeClient
     * @throws AuthenticationException
     * @throws StorageException
     */
    private static function createCmlifeClientWithCookieValuesAuthentication(array $credentials): CmlifeClient
    {
        $authenticator = CookieValuesAuthenticator::createWithDefaultHttpClient();
        $authenticator->authenticate($credentials);
        $dataClient = DataClient::createWithDefaultHttpClient($authenticator);
        $dataStorage = DataStorage::createWithInMemorySqliteDatabase();

        return new CmlifeClient($dataClient, $dataStorage);
    }

    /**
     * @param array{'username': string, 'password': string} $credentials
     * @return CmlifeClient
     * @throws AuthenticationException
     * @throws StorageException
     */
    private static function createCmlifeClientWithUsernameAndPasswordAuthentication(array $credentials): CmlifeClient
    {
        $authenticator = UsernamePasswordAuthenticator::createWithDefaultHttpClient();
        $authenticator->authenticate($credentials);
        $dataClient = DataClient::createWithDefaultHttpClient($authenticator);
        $dataStorage = DataStorage::createWithInMemorySqliteDatabase();

        return new CmlifeClient($dataClient, $dataStorage);
    }
}
