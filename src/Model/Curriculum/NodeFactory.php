<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Model\Curriculum;

use ITB\CmlifeClient\Model\Curriculum\Node\AssessmentNode;
use ITB\CmlifeClient\Model\Curriculum\Node\LinkNode;
use ITB\CmlifeClient\Model\Curriculum\Node\OfferNode;
use ITB\CmlifeClient\Model\Curriculum\Node\PartNode;
use ITB\CmlifeClient\Model\Curriculum\Node\RootNode;
use ITB\CmlifeClient\Model\Curriculum\Node\RuleNode;
use ITB\CmlifeClient\Model\Curriculum\Node\ModuleNode;

final class NodeFactory
{
    /** @var array<string, class-string<NodeInterface>> */
    public const NODE_TYPE_TO_CLASS_MAP = [
        AssessmentNode::TYPE => AssessmentNode::class,
        LinkNode::TYPE => LinkNode::class,
        ModuleNode::TYPE => ModuleNode::class,
        OfferNode::TYPE => OfferNode::class,
        PartNode::TYPE => PartNode::class,
        RootNode::TYPE => RootNode::class,
        RuleNode::TYPE => RuleNode::class
    ];

    /**
     * @param array<string, mixed> $nodeData
     * @param NodeInterface|null $parent
     * @return NodeInterface
     */
    public static function createNode(array $nodeData, ?NodeInterface $parent = null): NodeInterface
    {
        // Before the node is created, all children are created and then the data is removed from this node --> Leaf-to-Root-Construction.
        if (!array_key_exists('children', $nodeData)) {
            $nodeData['children'] = [];
        }

        $nodeClassName = self::NODE_TYPE_TO_CLASS_MAP[strtolower($nodeData['type'])];

        return $nodeClassName::create($nodeData, $parent);
    }
}
