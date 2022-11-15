<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Model\Curriculum\Node;

use Generator;
use ITB\CmlifeClient\Model\Curriculum\Node\RootNode;
use PHPUnit\Framework\TestCase;

final class RootNodeTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestGetType(): Generator
    {
        include_once __DIR__ . '/../../../Fixtures/node/root_node.php';
        $node = RootNode::create(getRootNodeData());

        yield [$node];
    }

    /**
     * @dataProvider provideForTestGetType
     *
     * @param RootNode $node
     * @return void
     */
    public function testGetType(RootNode $node): void
    {
        $this->assertEquals('root', $node->getType());
    }
}
