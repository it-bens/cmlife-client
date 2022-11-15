<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests;

use Doctrine\ORM\Exception\ORMException;
use Generator;
use ITB\CmlifeClient\Authentication\UsernamePasswordAuthenticator;
use ITB\CmlifeClient\CmlifeClient;
use ITB\CmlifeClient\Exception\AuthenticationException;
use ITB\CmlifeClient\Exception\CmlifeDataNotFetchedException;
use ITB\CmlifeClient\Exception\StorageException;
use ITB\CmlifeClient\Model\Semester;
use PHPUnit\Framework\TestCase;

final class CmlifeClientWithoutInitializationTest extends TestCase
{
    use CreateDoctrineDataStorageTrait;
    use CreateCmlifeClientTrait;

    private static CmlifeClient $cmlifeClient;

    /**
     * @return void
     * @throws AuthenticationException
     * @throws ORMException
     */
    public static function setUpBeforeClass(): void
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

        self::$cmlifeClient = $cmlifeClient;
    }

    /**
     * @return Generator
     */
    public function provideForTestGetCourses(): Generator
    {
        yield [new Semester(1, '', '', true)];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetCurrentPerson(): Generator
    {
        yield [$_ENV['CMLIFE_USERNAME']];
    }

    /**
     * @dataProvider provideForTestGetCourses
     *
     * @param Semester $semester
     * @return void
     */
    public function testGetCourses(Semester $semester): void
    {
        $cmlifeClient = self::$cmlifeClient;
        $this->expectException(CmlifeDataNotFetchedException::class);
        $cmlifeClient->getCourses($semester);
    }

    /**
     * @return void
     */
    public function testGetCoursesForStudyAndSemester(): void
    {
        $cmlifeClient = self::$cmlifeClient;
        $this->expectException(CmlifeDataNotFetchedException::class);
        $cmlifeClient->getCourses(new Semester(1, '', '', true));
    }

    /**
     * @return void
     * @throws StorageException
     */
    public function testGetCurrentPerson(): void
    {
        $cmlifeClient = self::$cmlifeClient;
        $this->expectException(CmlifeDataNotFetchedException::class);
        $cmlifeClient->getCurrentPerson();
    }

    /**
     * @return void
     * @throws StorageException
     */
    public function testGetCurrentSemester(): void
    {
        $cmlifeClient = self::$cmlifeClient;
        $this->expectException(CmlifeDataNotFetchedException::class);
        $cmlifeClient->getCurrentSemester();
    }

    /**
     * @return void
     */
    public function testGetMyStudies(): void
    {
        $cmlifeClient = self::$cmlifeClient;
        $this->expectException(CmlifeDataNotFetchedException::class);
        $cmlifeClient->getMyStudies();
    }
}
