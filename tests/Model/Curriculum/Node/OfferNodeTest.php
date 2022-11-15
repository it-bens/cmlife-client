<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Model\Curriculum\Node;

use Generator;
use ITB\CmlifeClient\Model\Curriculum\Node\OfferNode;
use PHPUnit\Framework\TestCase;

final class OfferNodeTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestGetType(): Generator
    {
        include_once __DIR__ . '/../../../Fixtures/node/offer_node.php';
        $node = OfferNode::create(getOfferNodeData());

        yield [$node];
    }

    /**
     * @dataProvider provideForTestGetType
     *
     * @param OfferNode $node
     * @return void
     */
    public function testGetType(OfferNode $node): void
    {
        $this->assertEquals('offer', $node->getType());
    }
}
