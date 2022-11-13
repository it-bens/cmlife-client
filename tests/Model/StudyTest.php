<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Model;

use Generator;
use ITB\CmlifeClient\Model\Study;
use PHPUnit\Framework\TestCase;

final class StudyTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestCreateStudy(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';

        yield [getStudyData(), true];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetCurriculum(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $study = Study::create(getStudyData());

        yield [$study, getStudyData()['curriculum']['id']];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetId(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $study = Study::create(getStudyData());

        yield [$study, getStudyData()['id']];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetProgramDegree(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $study = Study::create(getStudyData());

        yield [$study, getStudyData()['program']['degreeGoal']['nameDe']];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetProgramSubject(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $study = Study::create(getStudyData());

        yield [$study, getStudyData()['program']['subject']['nameDe']];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetUri(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $study = Study::create(getStudyData());

        yield [$study, getStudyData()['uri']];
    }

    /**
     * @return Generator
     */
    public function provideForTestUpdate(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $study = Study::create(getStudyData());
        $studyUpdate = Study::create(getStudyUpdateData());

        yield [$study, $studyUpdate];
    }

    /**
     * @dataProvider provideForTestCreateStudy
     *
     * @param array<string, mixed> $studyData
     * @return void
     */
    public function testCreateStudy(array $studyData): void
    {
        $study = Study::create($studyData);

        $this->assertInstanceOf(Study::class, $study);
    }

    /**
     * @dataProvider provideForTestGetCurriculum
     *
     * @param Study $study
     * @param int $expectedCurriculumId
     * @return void
     */
    public function testGetCurriculum(Study $study, int $expectedCurriculumId): void
    {
        $this->assertEquals($expectedCurriculumId, $study->getCurriculum()->getId());
    }

    /**
     * @dataProvider provideForTestGetId
     *
     * @param Study $study
     * @param int $expectedId
     * @return void
     */
    public function testGetId(Study $study, int $expectedId): void
    {
        $this->assertEquals($expectedId, $study->getId());
    }

    /**
     * @dataProvider provideForTestGetProgramDegree
     *
     * @param Study $study
     * @param string $expectedProgramDegree
     * @return void
     */
    public function testGetProgramDegree(Study $study, string $expectedProgramDegree): void
    {
        $this->assertEquals($expectedProgramDegree, $study->getProgramDegree());
    }

    /**
     * @dataProvider provideForTestGetProgramSubject
     *
     * @param Study $study
     * @param string $expectedProgramSubject
     * @return void
     */
    public function testGetProgramSubject(Study $study, string $expectedProgramSubject): void
    {
        $this->assertEquals($expectedProgramSubject, $study->getProgramSubject());
    }

    /**
     * @dataProvider provideForTestGetUri
     *
     * @param Study $study
     * @param string $expectedUri
     * @return void
     */
    public function testGetUri(Study $study, string $expectedUri): void
    {
        $this->assertEquals($expectedUri, $study->getUri());
    }

    /**
     * @dataProvider provideForTestUpdate
     *
     * @param Study $study
     * @param Study $studyUpdate
     * @return void
     */
    public function testUpdate(Study $study, Study $studyUpdate): void
    {
        $this->assertNotEquals($studyUpdate->getProgramSubject(), $study->getProgramSubject());
        $this->assertNotEquals($studyUpdate->getProgramDegree(), $study->getProgramDegree());
        $this->assertNotEquals($studyUpdate->getCurriculum()->getCurriculumDocumentUrl(), $study->getCurriculum()->getCurriculumDocumentUrl());

        $study->update($studyUpdate);
        $this->assertEquals($studyUpdate->getProgramSubject(), $study->getProgramSubject());
        $this->assertEquals($studyUpdate->getProgramDegree(), $study->getProgramDegree());
        $this->assertEquals($studyUpdate->getCurriculum()->getCurriculumDocumentUrl(), $study->getCurriculum()->getCurriculumDocumentUrl());
    }
}
