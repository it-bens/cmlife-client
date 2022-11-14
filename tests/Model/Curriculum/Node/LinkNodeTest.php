<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Model\Curriculum\Node;

use Generator;
use ITB\CmlifeClient\Model\Curriculum\Node\LinkNode;
use PHPUnit\Framework\TestCase;

final class LinkNodeTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestGetCourseEquivalenceUri(): Generator
    {
        include_once __DIR__ . '/../../../Fixtures/node/link_node.php';
        $node = LinkNode::create(getLinkNodeData());

        yield [$node, getLinkNodeData()['courseEquivalenceUri']];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetType(): Generator
    {
        include_once __DIR__ . '/../../../Fixtures/node/link_node.php';
        $node = LinkNode::create(getLinkNodeData());

        yield [$node];
    }

    /**
     * @dataProvider provideForTestGetCourseEquivalenceUri
     *
     * @param LinkNode $node
     * @param string $expectedEquivalenceUri
     * @return void
     */
    public function testGetCourseEquivalenceUri(LinkNode $node, string $expectedEquivalenceUri): void
    {
        $this->assertEquals($expectedEquivalenceUri, $node->getCourseEquivalenceUri());
    }

    /**
     * @dataProvider provideForTestGetType
     *
     * @param LinkNode $node
     * @return void
     */
    public function testGetType(LinkNode $node): void
    {
        $this->assertEquals('link', $node->getType());
    }
}