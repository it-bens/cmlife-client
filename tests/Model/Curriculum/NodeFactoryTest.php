<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Model\Curriculum;

use Generator;
use ITB\CmlifeClient\Model\Curriculum\Node\AssessmentNode;
use ITB\CmlifeClient\Model\Curriculum\Node\LinkNode;
use ITB\CmlifeClient\Model\Curriculum\Node\ModuleNode;
use ITB\CmlifeClient\Model\Curriculum\Node\OfferNode;
use ITB\CmlifeClient\Model\Curriculum\Node\RootNode;
use ITB\CmlifeClient\Model\Curriculum\Node\RuleNode;
use ITB\CmlifeClient\Model\Curriculum\NodeFactory;
use PHPUnit\Framework\TestCase;

final class NodeFactoryTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestCreateNode(): Generator
    {
        include_once __DIR__ . '/../../Fixtures/node/assessment_node.php';
        yield 'assessment node' => [getAssessmentNodeData(), AssessmentNode::class];

        include_once __DIR__ . '/../../Fixtures/node/link_node.php';
        yield 'link node' => [getLinkNodeData(), LinkNode::class];

        include_once __DIR__ . '/../../Fixtures/node/module_node.php';
        yield 'module node' => [getModuleNodeData(), ModuleNode::class];

        include_once __DIR__ . '/../../Fixtures/node/offer_node.php';
        yield 'offer node' => [getOfferNodeData(), OfferNode::class];

        include_once __DIR__ . '/../../Fixtures/node/root_node.php';
        yield 'root node' => [getRootNodeData(), RootNode::class];

        include_once __DIR__ . '/../../Fixtures/node/rule_node.php';
        yield 'rule node' => [getRuleNodeData(), RuleNode::class];
    }

    /**
     * @dataProvider provideForTestCreateNode
     *
     * @param array<string, mixed> $nodeData
     * @param class-string $expectedNodeType
     * @return void
     */
    public function testCreateNode(array $nodeData, string $expectedNodeType): void
    {
        $node = NodeFactory::createNode($nodeData);
        $this->assertInstanceOf($expectedNodeType, $node);
    }
}
