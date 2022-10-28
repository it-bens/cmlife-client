<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Model;

use Generator;
use ITB\CmlifeClient\Model\Curriculum;
use PHPUnit\Framework\TestCase;

final class CurriculumTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestGetCurriculumDocumentUrl(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $curriculum = Curriculum::create(getCurriculumData());

        yield [$curriculum, getCurriculumData()['curriculumDocumentUrl']];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetId(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $curriculum = Curriculum::create(getCurriculumData());

        yield [$curriculum, getCurriculumData()['id']];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetUri(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $curriculum = Curriculum::create(getCurriculumData());

        yield [$curriculum, getCurriculumData()['uri']];
    }

    /**
     * @dataProvider provideForTestGetCurriculumDocumentUrl
     *
     * @param Curriculum $curriculum
     * @param string $expectedCurriculumDocumentUrl
     * @return void
     */
    public function testGetCurriculumDocumentUrl(Curriculum $curriculum, string $expectedCurriculumDocumentUrl): void
    {
        $this->assertEquals($expectedCurriculumDocumentUrl, $curriculum->getCurriculumDocumentUrl());
    }

    /**
     * @dataProvider provideForTestGetId
     *
     * @param Curriculum $curriculum
     * @param int $expectedId
     * @return void
     */
    public function testGetId(Curriculum $curriculum, int $expectedId): void
    {
        $this->assertEquals($expectedId, $curriculum->getId());
    }

    /**
     * @dataProvider provideForTestGetUri
     *
     * @param Curriculum $curriculum
     * @param string $expectedUri
     * @return void
     */
    public function testGetUri(Curriculum $curriculum, string $expectedUri): void
    {
        $this->assertEquals($expectedUri, $curriculum->getUri());
    }

    /**
     * @return Generator
     */
    public function provideForTestGetRootNode(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $curriculum = Curriculum::create(getCurriculumData());

        yield [$curriculum, getCurriculumData()['root']['id']];
    }

    /**
     * @dataProvider provideForTestGetRootNode
     *
     * @param Curriculum $curriculum
     * @param int $expectedRootNodeId
     * @return void
     */
    public function testGetGetRootNode(Curriculum $curriculum, int $expectedRootNodeId): void
    {
        $this->assertEquals($expectedRootNodeId, $curriculum->getRootNode()->getId());
    }

    /**
     * @return Generator
     */
    public function provideForTestGetStudyPlanUrl(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $curriculum = Curriculum::create(getCurriculumData());

        yield [$curriculum, getCurriculumData()['studyPlanUrl']];
    }

    /**
     * @dataProvider provideForTestGetStudyPlanUrl
     *
     * @param Curriculum $curriculum
     * @param string $expectedStudyPlanUrl
     * @return void
     */
    public function testGetStudyPlanUrl(Curriculum $curriculum, string $expectedStudyPlanUrl): void
    {
        $this->assertEquals($expectedStudyPlanUrl, $curriculum->getStudyPlanUrl());
    }
}
