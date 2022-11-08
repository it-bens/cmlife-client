<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests;

use Generator;
use ITB\CmlifeClient\Authentication\UsernamePasswordAuthenticator;
use ITB\CmlifeClient\CmlifeClient;
use ITB\CmlifeClient\CmlifeClientInterface;
use ITB\CmlifeClient\Connection\DataClient;
use ITB\CmlifeClient\Exception\AuthenticationException;
use ITB\CmlifeClient\Exception\CourseRetrievalFailedException;
use ITB\CmlifeClient\Exception\PersonRetrievalFailedException;
use ITB\CmlifeClient\Exception\SemesterRetrievalFailedException;
use ITB\CmlifeClient\Exception\StorageException;
use ITB\CmlifeClient\Exception\StudyRetrievalFailedException;
use ITB\CmlifeClient\Storage\DataStorage;
use ITB\CmlifeClient\Tests\Mock\DataClientMockThatCallsFailure;
use PHPUnit\Framework\TestCase;
use Throwable;

final class CmlifeClientCreateAndInitializeTest extends TestCase
{
    private const FETCH_SEMESTER_URI_PATTERN = '/\/v3\/cmco\/api\/semesters\/\d+/';
    private const FETCH_PERSON_URI_PATTERN = '/\/v3\/cmco\/api\/user/';
    private const FETCH_COURSES_URI_PATTERN = '/\/v3\/cmco\/api\/courses\/pages\/searches/';
    // private const FETCH_MY_STUDIES_URI_PATTERN = '/\/v3\/cmco\/api\/studies\/searches/';
    private const FETCH_CURRICULUM_URI_PATTERN = '/\/v3\/cmco\/api\/studies\/\d+/';

    /**
     * @return Generator
     */
    public function provideForTestCreateWithValidCredentials(): Generator
    {
        yield [$_ENV['CMLIFE_USERNAME'], $_ENV['CMLIFE_PASSWORD']];
    }

    /**
     * @return Generator
     * @throws AuthenticationException
     * @throws StorageException
     */
    public function provideForTestFetchDataFromCmlifeWithDataClientExceptions(): Generator
    {
        $authenticator = UsernamePasswordAuthenticator::createWithDefaultHttpClient();
        $authenticator->authenticate(
            [
                UsernamePasswordAuthenticator::CREDENTIAL_NAME_USERNAME => $_ENV['CMLIFE_USERNAME'],
                UsernamePasswordAuthenticator::CREDENTIAL_NAME_PASSWORD => $_ENV['CMLIFE_PASSWORD']
            ]
        );
        $originalDataClient = DataClient::createWithDefaultHttpClient($authenticator);

        $dataStorage = new DataStorage(['driver' => 'pdo_sqlite', 'path' => '/tmp' . uniqid(more_entropy: true) . '.sqlite']);
        $dataClient = new DataClientMockThatCallsFailure($originalDataClient, self::FETCH_SEMESTER_URI_PATTERN);
        yield 'fetch semester' => [new CmlifeClient($dataClient, $dataStorage), SemesterRetrievalFailedException::class];

        $dataStorage = new DataStorage(['driver' => 'pdo_sqlite', 'path' => '/tmp' . uniqid(more_entropy: true) . '.sqlite']);
        $dataClient = new DataClientMockThatCallsFailure($originalDataClient, self::FETCH_PERSON_URI_PATTERN);
        yield 'fetch person' => [new CmlifeClient($dataClient, $dataStorage), PersonRetrievalFailedException::class];

        $dataStorage = new DataStorage(['driver' => 'pdo_sqlite', 'path' => '/tmp' . uniqid(more_entropy: true) . '.sqlite']);
        $dataClient = new DataClientMockThatCallsFailure($originalDataClient, self::FETCH_COURSES_URI_PATTERN);
        yield 'fetch courses' => [new CmlifeClient($dataClient, $dataStorage), CourseRetrievalFailedException::class];

        $dataStorage = new DataStorage(['driver' => 'pdo_sqlite', 'path' => '/tmp' . uniqid(more_entropy: true) . '.sqlite']);
        $dataClient = new DataClientMockThatCallsFailure($originalDataClient, self::FETCH_CURRICULUM_URI_PATTERN);
        yield 'fetch curriculum' => [new CmlifeClient($dataClient, $dataStorage), StudyRetrievalFailedException::class];
    }

    /**
     * @dataProvider provideForTestCreateWithValidCredentials
     *
     * @param string $username
     * @param string $password
     * @return void
     * @throws AuthenticationException
     * @throws StorageException
     */
    public function testCreateWithValidCredentials(string $username, string $password): void
    {
        $cmlifeClient = CmlifeClient::createWithUsernameAndPasswordAuthentication(
            [
                UsernamePasswordAuthenticator::CREDENTIAL_NAME_USERNAME => $username,
                UsernamePasswordAuthenticator::CREDENTIAL_NAME_PASSWORD => $password
            ]
        );
        $cmlifeClient->fetchDataFromCmlife();

        $this->assertInstanceOf(CmlifeClientInterface::class, $cmlifeClient);
    }

    /**
     * @dataProvider provideForTestFetchDataFromCmlifeWithDataClientExceptions
     *
     * @param CmlifeClient $cmlifeClient
     * @param class-string<Throwable> $expectedException
     * @return void
     * @throws AuthenticationException
     * @throws StorageException
     */
    public function testFetchDataFromCmlifeWithDataClientExceptions(CmlifeClient $cmlifeClient, string $expectedException): void
    {
        $this->expectException($expectedException);
        $cmlifeClient->fetchDataFromCmlife();
    }
}
