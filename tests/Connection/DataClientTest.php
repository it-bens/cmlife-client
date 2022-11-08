<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Connection;

use Generator;
use ITB\CmlifeClient\Authentication\UsernamePasswordAuthenticator;
use ITB\CmlifeClient\Connection\DataClient;
use ITB\CmlifeClient\Connection\RequestData;
use ITB\CmlifeClient\Exception\AuthenticationException;
use LogicException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class DataClientTest extends TestCase
{
    private static DataClient $dataClientWithHttpClientThrowingExceptions;

    /**
     * @return void
     * @throws AuthenticationException
     */
    public static function setUpBeforeClass(): void
    {
        $authenticator = UsernamePasswordAuthenticator::createWithDefaultHttpClient();
        $authenticator->authenticate(
            [
                UsernamePasswordAuthenticator::CREDENTIAL_NAME_USERNAME => $_ENV['CMLIFE_USERNAME'],
                UsernamePasswordAuthenticator::CREDENTIAL_NAME_PASSWORD => $_ENV['CMLIFE_PASSWORD']
            ]
        );

        $httpClient = (new DataClientTest())->createMock(HttpClientInterface::class);
        $httpClient->expects((new DataClientTest())->any())->method('request')->willThrowException(
            new RuntimeException('I was thrown by a mock when the request() of the http client was called.')
        );

        self::$dataClientWithHttpClientThrowingExceptions = new DataClient($authenticator, $httpClient);
    }

    /**
     * @return Generator
     */
    public function provideForTestFetchAllDataAsyncWithHttpClientThrowingException(): Generator
    {
        $onRequestFailure = function () {
            echo 'This is printed from the failure callback.';
        };
        $onRequestSuccess = function () {
            echo 'This is printed from the success callback.';
        };

        yield [[new RequestData('GET', 'no', null, null)], $onRequestFailure, $onRequestSuccess];
    }

    /**
     * @return Generator
     */
    public function provideForTestFetchDataAsyncWithHttpClientThrowingException(): Generator
    {
        $onRequestFailure = function () {
            echo 'This is printed from the failure callback.';
        };
        $onRequestSuccess = function () {
            echo 'This is printed from the success callback.';
        };

        yield [new RequestData('GET', 'no', null, null), $onRequestFailure, $onRequestSuccess];
    }

    /**
     * @return Generator
     */
    public function provideForTestFetchDataSyncWithHttpClientThrowingException(): Generator
    {
        yield [new RequestData('GET', 'no', null, null)];
    }

    /**
     * @dataProvider provideForTestFetchAllDataAsyncWithHttpClientThrowingException
     *
     * @param RequestData[] $requestDatasets
     * @param callable $onRequestFailure
     * @param callable $onRequestSuccess
     * @return void
     * @throws AuthenticationException
     */
    public function testFetchAllDataAsyncWithHttpClientThrowingException(
        array $requestDatasets,
        callable $onRequestFailure,
        callable $onRequestSuccess
    ): void {
        $dataClient = self::$dataClientWithHttpClientThrowingExceptions;
        $this->expectException(LogicException::class);
        $dataClient->fetchAllDataAsync($requestDatasets, $onRequestFailure, $onRequestSuccess);
    }

    /**
     * @dataProvider provideForTestFetchDataAsyncWithHttpClientThrowingException
     *
     * @param RequestData $requestData
     * @param callable $onRequestFailure
     * @param callable $onRequestSuccess
     * @return void
     * @throws AuthenticationException
     */
    public function testFetchDataAsyncWithHttpClientThrowingException(
        RequestData $requestData,
        callable $onRequestFailure,
        callable $onRequestSuccess
    ): void {
        $dataClient = self::$dataClientWithHttpClientThrowingExceptions;
        $this->expectException(LogicException::class);
        $dataClient->fetchDataAsync($requestData, $onRequestFailure, $onRequestSuccess);
    }

    /**
     * @dataProvider provideForTestFetchDataSyncWithHttpClientThrowingException
     *
     * @param RequestData $requestData
     * @return void
     * @throws AuthenticationException
     */
    public function testFetchDataSyncWithHttpClientThrowingException(RequestData $requestData,): void
    {
        $dataClient = self::$dataClientWithHttpClientThrowingExceptions;
        $this->expectException(RuntimeException::class);
        $dataClient->fetchDataSync($requestData);
    }
}
