<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests;

use Generator;
use ITB\CmlifeClient\Authentication\UsernamePasswordAuthenticator;
use ITB\CmlifeClient\CmlifeClient;
use ITB\CmlifeClient\Exception\AuthenticationException;
use ITB\CmlifeClient\Exception\StorageException;
use ITB\CmlifeClient\Model\Course;
use ITB\CmlifeClient\Model\Person;
use ITB\CmlifeClient\Model\Semester;
use ITB\CmlifeClient\Model\Study;
use PHPUnit\Framework\TestCase;

final class CmlifeClientWithInitializationTest extends TestCase
{
    private static CmlifeClient $cmlifeClient;

    /**
     * @return void
     * @throws AuthenticationException
     * @throws StorageException
     */
    public static function setUpBeforeClass(): void
    {
        $cmlifeClient = CmlifeClient::createWithUsernameAndPasswordAuthentication(
            [
                UsernamePasswordAuthenticator::CREDENTIAL_NAME_USERNAME => $_ENV['CMLIFE_USERNAME'],
                UsernamePasswordAuthenticator::CREDENTIAL_NAME_PASSWORD => $_ENV['CMLIFE_PASSWORD']
            ]
        );
        $cmlifeClient->fetchDataFromCmlife();

        self::$cmlifeClient = $cmlifeClient;
    }

    /**
     * @return Generator
     */
    public function provideForTestGetCurrentPerson(): Generator
    {
        yield [$_ENV['CMLIFE_USERNAME']];
    }

    /**
     * @return void
     * @throws StorageException
     */
    public function testGetCourses(): void
    {
        $cmlifeClient = self::$cmlifeClient;
        $semester = $cmlifeClient->getCurrentSemester();
        $courses = $cmlifeClient->getCourses($semester);

        $this->assertNotEmpty($courses);
        $this->assertContainsOnlyInstancesOf(Course::class, $courses);
    }

    /**
     * @return void
     * @throws StorageException
     */
    public function testGetCoursesForStudyAndSemester(): void
    {
        $cmlifeClient = self::$cmlifeClient;
        $study = $cmlifeClient->getMyStudies()[0];
        $semester = $cmlifeClient->getCurrentSemester();

        $courses = $cmlifeClient->getCoursesForStudyAndSemester($study, $semester);
        $allCoursesInSemester = $cmlifeClient->getCourses($semester);

        $this->assertNotEmpty($courses);
        $this->assertNotEquals(count($allCoursesInSemester), count($courses));
        $this->assertContainsOnlyInstancesOf(Course::class, $courses);
    }

    /**
     * @dataProvider provideForTestGetCurrentPerson
     *
     * @param string $username
     * @return void
     * @throws StorageException
     */
    public function testGetCurrentPerson(string $username): void
    {
        $cmlifeClient = self::$cmlifeClient;
        $person = $cmlifeClient->getCurrentPerson();

        $this->assertInstanceOf(Person::class, $person);
        $this->assertTrue($person->isMe());
        $this->assertEquals($username, $person->getUsername());
    }

    /**
     * @return void
     * @throws StorageException
     */
    public function testGetCurrentSemester(): void
    {
        $cmlifeClient = self::$cmlifeClient;
        $semester = $cmlifeClient->getCurrentSemester();

        $this->assertInstanceOf(Semester::class, $semester);
        $this->assertTrue($semester->isCurrent());
    }

    /**
     * @return void
     */
    public function testGetMyStudies(): void
    {
        $cmlifeClient = self::$cmlifeClient;
        $studies = $cmlifeClient->getMyStudies();

        $this->assertNotEmpty($studies);
        foreach ($studies as $study) {
            $this->assertInstanceOf(Study::class, $study);
        }
    }
}
