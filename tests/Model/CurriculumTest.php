<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Model;

use Generator;
use ITB\CmlifeClient\Model\Curriculum;
use ITB\CmlifeClient\Model\Person;
use LogicException;
use PHPUnit\Framework\TestCase;

final class CurriculumTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestCreateCurriculum(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';

        yield [getCurriculumData()];
    }

    /**
     * @return Generator
     */
    public function provideForTestCreateCurriculumWithWrongRootNodeType(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $wrongNodeType = getCurriculumDataWithWrongRootNodeType()['root']['type'];

        yield [getCurriculumDataWithWrongRootNodeType(), Curriculum\NodeFactory::NODE_TYPE_TO_CLASS_MAP[strtolower($wrongNodeType)]];
    }

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
    public function provideForTestGetRootNode(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $curriculum = Curriculum::create(getCurriculumData());

        yield [$curriculum, getCurriculumData()['root']['id']];
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
     * @return Generator
     */
    public function provideForTestGetUri(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $curriculum = Curriculum::create(getCurriculumData());

        yield [$curriculum, getCurriculumData()['uri']];
    }

    /**
     * @return Generator
     */
    public function provideForTestUpdate(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $curriculum = Curriculum::create(getCurriculumData());
        $curriculumUpdate = Curriculum::create(getCurriculumUpdateData());

        yield [$curriculum, $curriculumUpdate];
    }

    /**
     * @dataProvider provideForTestCreateCurriculum
     *
     * @param array<string, mixed> $curriculumData
     * @return void
     */
    public function testCreateCurriculum(array $curriculumData): void
    {
        $curriculum = Curriculum::create($curriculumData);

        $this->assertInstanceOf(Curriculum::class, $curriculum);
    }

    /**
     * @dataProvider provideForTestCreateCurriculumWithWrongRootNodeType
     *
     * @param array<string, mixed> $curriculumData
     * @param string $wrongNodeType
     * @return void
     */
    public function testCreateCurriculumWithWrongRootNodeType(array $curriculumData, string $wrongNodeType): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(sprintf('The root of the curriculums node tree should always be a root node, but it is a %s', $wrongNodeType));
        Curriculum::create($curriculumData);
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
     * @dataProvider provideForTestUpdate
     *
     * @param Curriculum $curriculum
     * @param Curriculum $curriculumUpdate
     * @return void
     */
    public function testUpdate(Curriculum $curriculum, Curriculum $curriculumUpdate): void
    {
        $this->assertNotEquals($curriculumUpdate->getStudyPlanUrl(), $curriculum->getStudyPlanUrl());
        $this->assertNotEquals($curriculumUpdate->getCurriculumDocumentUrl(), $curriculum->getCurriculumDocumentUrl());
        $this->assertNotEquals($curriculumUpdate->getRootNode()->getName(), $curriculum->getRootNode()->getName());

        $curriculum->update($curriculumUpdate);

        $this->assertEquals($curriculumUpdate->getStudyPlanUrl(), $curriculum->getStudyPlanUrl());
        $this->assertEquals($curriculumUpdate->getCurriculumDocumentUrl(), $curriculum->getCurriculumDocumentUrl());
        $this->assertEquals($curriculumUpdate->getRootNode()->getName(), $curriculum->getRootNode()->getName());
    }
}
