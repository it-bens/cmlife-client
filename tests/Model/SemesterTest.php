<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Model;

use Generator;
use ITB\CmlifeClient\Model\Semester;
use PHPUnit\Framework\TestCase;

final class SemesterTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestCreateCurrentSemester(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';

        yield [getCurrentSemesterData()];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetId(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $currentSemester = Semester::create(getCurrentSemesterData());
        include_once __DIR__ . '/../Fixtures/previous_semester.php';
        $previousSemester = Semester::create(getPreviousSemesterData());

        yield 'current semester' => [$currentSemester, getCurrentSemesterData()['id']];
        yield 'previous semester' => [$previousSemester, getPreviousSemesterData()['id']];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetName(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $currentSemester = Semester::create(getCurrentSemesterData());
        include_once __DIR__ . '/../Fixtures/previous_semester.php';
        $previousSemester = Semester::create(getPreviousSemesterData());

        yield 'current semester' => [$currentSemester, getCurrentSemesterData()['name']];
        yield 'previous semester' => [$previousSemester, getPreviousSemesterData()['name']];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetUri(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $currentSemester = Semester::create(getCurrentSemesterData());
        include_once __DIR__ . '/../Fixtures/previous_semester.php';
        $previousSemester = Semester::create(getPreviousSemesterData());

        yield 'current semester' => [$currentSemester, getCurrentSemesterData()['uri']];
        yield 'previous semester' => [$previousSemester, getPreviousSemesterData()['uri']];
    }

    public function provideForTestIsCurrent(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $currentSemester = Semester::create(getCurrentSemesterData());
        include_once __DIR__ . '/../Fixtures/previous_semester.php';
        $previousSemester = Semester::create(getPreviousSemesterData());

        yield 'current semester' => [$currentSemester, getCurrentSemesterData()['current']];
        yield 'previous semester' => [$previousSemester, getPreviousSemesterData()['current']];
    }

    /**
     * @return Generator
     */
    public function provideForTestUpdate(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $currentSemester = Semester::create(getCurrentSemesterData());
        $currentSemesterUpdate = Semester::create(getCurrentSemesterUpdateData());

        yield [$currentSemester, $currentSemesterUpdate];
    }

    /**
     * @dataProvider provideForTestCreateCurrentSemester
     *
     * @param array<string, mixed> $semesterData
     * @return void
     */
    public function testCreateCurrentSemester(array $semesterData): void
    {
        $semester = Semester::create($semesterData);

        $this->assertInstanceOf(Semester::class, $semester);
    }

    /**
     * @dataProvider provideForTestGetId
     *
     * @param Semester $semester
     * @param int $expectedId
     * @return void
     */
    public function testGetId(Semester $semester, int $expectedId): void
    {
        $this->assertEquals($expectedId, $semester->getId());
    }

    /**
     * @dataProvider provideForTestGetName
     *
     * @param Semester $semester
     * @param string $expectedName
     * @return void
     */
    public function testGetName(Semester $semester, string $expectedName): void
    {
        $this->assertEquals($expectedName, $semester->getName());
    }

    /**
     * @dataProvider provideForTestGetUri
     *
     * @param Semester $semester
     * @param string $expectedUri
     * @return void
     */
    public function testGetUri(Semester $semester, string $expectedUri): void
    {
        $this->assertEquals($expectedUri, $semester->getUri());
    }

    /**
     * @dataProvider provideForTestIsCurrent
     *
     * @param Semester $semester
     * @param bool $expectedIsCurrent
     * @return void
     */
    public function testIsCurrent(Semester $semester, bool $expectedIsCurrent): void
    {
        $this->assertEquals($expectedIsCurrent, $semester->isCurrent());
    }

    /**
     * @dataProvider provideForTestUpdate
     *
     * @param Semester $semester
     * @param Semester $semesterUpdate
     * @return void
     */
    public function testUpdate(Semester $semester, Semester $semesterUpdate): void
    {
        $this->assertNotEquals($semesterUpdate->getName(), $semester->getName());
        $this->assertNotEquals($semesterUpdate->isCurrent(), $semester->isCurrent());

        $semester->update($semesterUpdate);
        $this->assertEquals($semesterUpdate->getName(), $semester->getName());
        $this->assertEquals($semesterUpdate->isCurrent(), $semester->isCurrent());
    }
}
