<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Model\Curriculum\Node;

use Generator;
use ITB\CmlifeClient\Model\Curriculum\Node\FocusNode;
use PHPUnit\Framework\TestCase;

final class FocusNodeTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestGetType(): Generator
    {
        include_once __DIR__ . '/../../../Fixtures/node/focus_node.php';
        $node = FocusNode::create(getFocusNodeData());

        yield [$node];
    }

    /**
     * @dataProvider provideForTestGetType
     *
     * @param FocusNode $node
     * @return void
     */
    public function testGetType(FocusNode $node): void
    {
        $this->assertEquals('focus', $node->getType());
    }
}
