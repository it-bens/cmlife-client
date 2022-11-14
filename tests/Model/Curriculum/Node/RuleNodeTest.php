<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Model\Curriculum\Node;

use Generator;
use ITB\CmlifeClient\Model\Curriculum\Node\RuleNode;
use PHPUnit\Framework\TestCase;

final class RuleNodeTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestGetType(): Generator
    {
        include_once __DIR__ . '/../../../Fixtures/node/rule_node.php';
        $node = RuleNode::create(getRuleNodeData());

        yield [$node];
    }

    /**
     * @dataProvider provideForTestGetType
     *
     * @param RuleNode $node
     * @return void
     */
    public function testGetType(RuleNode $node): void
    {
        $this->assertEquals('rule', $node->getType());
    }
}