<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Model;

use Generator;
use ITB\CmlifeClient\Model\Course;
use ITB\CmlifeClient\Model\Semester;
use PHPUnit\Framework\TestCase;

final class CourseTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestCreateCourse(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $semester = Semester::create(getCurrentSemesterData());
        include_once __DIR__ . '/../Fixtures/course.php';

        yield [getCourseData(), $semester];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetEquivalenceUrl(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $semester = Semester::create(getCurrentSemesterData());
        include_once __DIR__ . '/../Fixtures/course.php';
        $course = Course::create(getCourseData(), $semester);

        yield [$course, getCourseData()['equivalenceUri']];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetFrontendUrl(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $semester = Semester::create(getCurrentSemesterData());
        include_once __DIR__ . '/../Fixtures/course.php';
        $course = Course::create(getCourseData(), $semester);

        yield [$course, getCourseData()['frontendUrl']];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetId(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $semester = Semester::create(getCurrentSemesterData());
        include_once __DIR__ . '/../Fixtures/course.php';
        $course = Course::create(getCourseData(), $semester);

        yield [$course, getCourseData()['id']];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetName(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $semester = Semester::create(getCurrentSemesterData());
        include_once __DIR__ . '/../Fixtures/course.php';
        $course = Course::create(getCourseData(), $semester);

        yield [$course, getCourseData()['nameDe']];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetSemester(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $semester = Semester::create(getCurrentSemesterData());
        include_once __DIR__ . '/../Fixtures/course.php';
        $course = Course::create(getCourseData(), $semester);

        yield [$course, $semester->getId()];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetUri(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $semester = Semester::create(getCurrentSemesterData());
        include_once __DIR__ . '/../Fixtures/course.php';
        $course = Course::create(getCourseData(), $semester);

        yield [$course, getCourseData()['uri']];
    }

    /**
     * @return Generator
     */
    public function provideFotTestGetCode(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $semester = Semester::create(getCurrentSemesterData());
        include_once __DIR__ . '/../Fixtures/course.php';
        $course = Course::create(getCourseData(), $semester);

        yield [$course, getCourseData()['code']];
    }

    /**
     * @dataProvider provideForTestCreateCourse
     *
     * @param array<string, mixed> $courseData
     * @param Semester $semester
     * @return void
     */
    public function testCreateCourse(array $courseData, Semester $semester): void
    {
        $course = Course::create($courseData, $semester);

        $this->assertInstanceOf(Course::class, $course);
    }

    /**
     * @dataProvider provideFotTestGetCode
     *
     * @param Course $course
     * @param string $expectedCode
     * @return void
     */
    public function testGetCode(Course $course, string $expectedCode): void
    {
        $this->assertEquals($expectedCode, $course->getCode());
    }

    /**
     * @dataProvider provideForTestGetEquivalenceUrl
     *
     * @param Course $course
     * @param string $expectedEquivalenceUri
     * @return void
     */
    public function testGetEquivalenceUri(Course $course, string $expectedEquivalenceUri): void
    {
        $this->assertEquals($expectedEquivalenceUri, $course->getEquivalenceUri());
    }

    /**
     * @dataProvider provideForTestGetFrontendUrl
     *
     * @param Course $course
     * @param string $expectedFrontendUrl
     * @return void
     */
    public function testGetFrontendUrl(Course $course, string $expectedFrontendUrl): void
    {
        $this->assertEquals($expectedFrontendUrl, $course->getFrontendUrl());
    }

    /**
     * @dataProvider provideForTestGetId
     *
     * @param Course $course
     * @param int $expectedId
     * @return void
     */
    public function testGetId(Course $course, int $expectedId): void
    {
        $this->assertEquals($expectedId, $course->getId());
    }

    /**
     * @dataProvider provideForTestGetName
     *
     * @param Course $course
     * @param string $expectedName
     * @return void
     */
    public function testGetName(Course $course, string $expectedName): void
    {
        $this->assertEquals($expectedName, $course->getName());
    }

    /**
     * @dataProvider provideForTestGetSemester
     *
     * @param Course $course
     * @param int $expectedSemesterId
     * @return void
     */
    public function testGetSemester(Course $course, int $expectedSemesterId): void
    {
        $this->assertEquals($expectedSemesterId, $course->getSemester()->getId());
    }

    /**
     * @dataProvider provideForTestGetUri
     *
     * @param Course $course
     * @param string $expectedUri
     * @return void
     */
    public function testGetUri(Course $course, string $expectedUri): void
    {
        $this->assertEquals($expectedUri, $course->getUri());
    }
}
