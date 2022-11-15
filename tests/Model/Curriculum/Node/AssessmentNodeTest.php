<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Model\Curriculum\Node;

use Generator;
use ITB\CmlifeClient\Model\Curriculum\Node\AssessmentNode;
use PHPUnit\Framework\TestCase;

final class AssessmentNodeTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestGetType(): Generator
    {
        include_once __DIR__ . '/../../../Fixtures/node/assessment_node.php';
        $node = AssessmentNode::create(getAssessmentNodeData());

        yield [$node];
    }

    /**
     * @dataProvider provideForTestGetType
     *
     * @param AssessmentNode $node
     * @return void
     */
    public function testGetType(AssessmentNode $node): void
    {
        $this->assertEquals('assessment', $node->getType());
    }
}
