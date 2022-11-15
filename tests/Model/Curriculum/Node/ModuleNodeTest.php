<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Model\Curriculum\Node;

use Generator;
use ITB\CmlifeClient\Model\Curriculum\Node\ModuleNode;
use PHPUnit\Framework\TestCase;

final class ModuleNodeTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestGetType(): Generator
    {
        include_once __DIR__ . '/../../../Fixtures/node/module_node.php';
        $node = ModuleNode::create(getModuleNodeData());

        yield [$node];
    }

    /**
     * @dataProvider provideForTestGetType
     *
     * @param ModuleNode $node
     * @return void
     */
    public function testGetType(ModuleNode $node): void
    {
        $this->assertEquals('module', $node->getType());
    }
}
