<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests;

use Doctrine\ORM\Exception\ORMException;
use Generator;
use ITB\CmlifeClient\Authentication\UsernamePasswordAuthenticator;
use ITB\CmlifeClient\CmlifeClient;
use ITB\CmlifeClient\Connection\DataClient;
use ITB\CmlifeClient\Exception\AuthenticationException;
use ITB\CmlifeClient\Exception\CourseRetrievalFailedException;
use ITB\CmlifeClient\Exception\PersonRetrievalFailedException;
use ITB\CmlifeClient\Exception\SemesterRetrievalFailedException;
use ITB\CmlifeClient\Exception\StorageException;
use ITB\CmlifeClient\Exception\StudyRetrievalFailedException;
use ITB\CmlifeClient\Tests\Mock\DataClientMockThatCallsFailure;
use PHPUnit\Framework\TestCase;
use Throwable;

final class CmlifeClientInitializeTest extends TestCase
{
    use CreateDoctrineDataStorageTrait;
    use CreateCmlifeClientTrait;

    private const FETCH_SEMESTER_URI_PATTERN = '/\/v3\/cmco\/api\/semesters\/\d+/';
    private const FETCH_PERSON_URI_PATTERN = '/\/v3\/cmco\/api\/user/';
    private const FETCH_COURSES_URI_PATTERN = '/\/v3\/cmco\/api\/courses\/pages\/searches/';
    // private const FETCH_MY_STUDIES_URI_PATTERN = '/\/v3\/cmco\/api\/studies\/searches/';
    private const FETCH_CURRICULUM_URI_PATTERN = '/\/v3\/cmco\/api\/studies\/\d+/';

    /**
     * @return Generator
     * @throws AuthenticationException
     * @throws ORMException
     */
    public function provideForTestFetchDataFromCmlife(): Generator
    {
        $dataStorage = self::createDoctrineDataStorage();
        $cmlifeClient = self::createCmlifeClientWithUsernameAndPasswordAuthentication(
            [
                UsernamePasswordAuthenticator::CREDENTIAL_NAME_USERNAME => $_ENV['CMLIFE_USERNAME'],
                UsernamePasswordAuthenticator::CREDENTIAL_NAME_PASSWORD => $_ENV['CMLIFE_PASSWORD']
            ],
            $dataStorage,
            $dataStorage
        );

        yield [$cmlifeClient];
    }

    /**
     * @return Generator
     * @throws AuthenticationException
     * @throws ORMException
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

        $dataStorage = self::createDoctrineDataStorage(['driver' => 'pdo_sqlite', 'path' => '/tmp' . uniqid(more_entropy: true) . '.sqlite']);
        $dataClient = new DataClientMockThatCallsFailure($originalDataClient, self::FETCH_SEMESTER_URI_PATTERN);
        yield 'fetch semester' => [new CmlifeClient($dataClient, $dataStorage, $dataStorage), SemesterRetrievalFailedException::class];

        $dataStorage = self::createDoctrineDataStorage(['driver' => 'pdo_sqlite', 'path' => '/tmp' . uniqid(more_entropy: true) . '.sqlite']);
        $dataClient = new DataClientMockThatCallsFailure($originalDataClient, self::FETCH_PERSON_URI_PATTERN);
        yield 'fetch person' => [new CmlifeClient($dataClient, $dataStorage, $dataStorage), PersonRetrievalFailedException::class];

        $dataStorage = self::createDoctrineDataStorage(['driver' => 'pdo_sqlite', 'path' => '/tmp' . uniqid(more_entropy: true) . '.sqlite']);
        $dataClient = new DataClientMockThatCallsFailure($originalDataClient, self::FETCH_COURSES_URI_PATTERN);
        yield 'fetch courses' => [new CmlifeClient($dataClient, $dataStorage, $dataStorage), CourseRetrievalFailedException::class];

        $dataStorage = self::createDoctrineDataStorage(['driver' => 'pdo_sqlite', 'path' => '/tmp' . uniqid(more_entropy: true) . '.sqlite']);
        $dataClient = new DataClientMockThatCallsFailure($originalDataClient, self::FETCH_CURRICULUM_URI_PATTERN);
        yield 'fetch curriculum' => [new CmlifeClient($dataClient, $dataStorage, $dataStorage), StudyRetrievalFailedException::class];
    }

    /**
     * @dataProvider provideForTestFetchDataFromCmlife
     *
     * @param CmlifeClient $cmlifeClient
     * @return void
     * @throws AuthenticationException
     * @throws StorageException
     */
    public function testFetchDataFromCmlife(CmlifeClient $cmlifeClient): void
    {
        $this->expectNotToPerformAssertions();
        $cmlifeClient->fetchDataFromCmlife();
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
