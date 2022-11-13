<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Model\Curriculum;

use Generator;
use ITB\CmlifeClient\Model\Curriculum\Node\LinkNode;
use ITB\CmlifeClient\Model\Curriculum\Node\RootNode;
use ITB\CmlifeClient\Model\Curriculum\NodeException\InvalidNodeTypeOnCreateException;
use ITB\CmlifeClient\Model\Curriculum\NodeException\InvalidNodeTypeOnUpdateException;
use ITB\CmlifeClient\Model\Curriculum\NodeInterface;
use LogicException;
use PHPUnit\Framework\TestCase;

final class NodeTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestBelongsToRootNode(): Generator
    {
        include_once __DIR__ . '/../../Fixtures/study.php';
        $rootNode = RootNode::create(getRootNodeDataFromCurriculum());

        // This walkthrough to the leaf node works because the test node tree has no branches.
        $leafNode = $rootNode;
        /** @phpstan-ignore-next-line */
        while (0 !== count($leafNode->getChildren())) {
            /** @phpstan-ignore-next-line */
            $leafNode = $leafNode->getChildren()->first();
        }
        yield [$leafNode, $rootNode, true];

        include_once __DIR__ . '/../../Fixtures/node/link_node.php';
        $leafNode = LinkNode::create(getLinkNodeData());
        yield [$leafNode, $rootNode, false];
    }

    /**
     * @return Generator
     */
    public function provideForTestCreateRootNode(): Generator
    {
        include_once __DIR__ . '/../../Fixtures/study.php';

        yield [getRootNodeDataFromCurriculum()];
    }

    /**
     * @return Generator
     */
    public function provideForTestCreateWithInvalidNodeType(): Generator
    {
        include_once __DIR__ . '/../../Fixtures/node/link_node.php';

        yield [getLinkNodeData()];
    }

    public function provideForTestGetChildren(): Generator
    {
        include_once __DIR__ . '/../../Fixtures/study.php';
        $rootNode = RootNode::create(getRootNodeDataFromCurriculum());
        $childrenCount = count(getRootNodeDataFromCurriculum()['children']);

        yield [$rootNode, $childrenCount];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetId(): Generator
    {
        include_once __DIR__ . '/../../Fixtures/study.php';
        $rootNode = RootNode::create(getRootNodeDataFromCurriculum());

        yield [$rootNode, getRootNodeDataFromCurriculum()['id']];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetName(): Generator
    {
        include_once __DIR__ . '/../../Fixtures/study.php';
        $rootNode = RootNode::create(getRootNodeDataFromCurriculum());

        yield [$rootNode, getRootNodeDataFromCurriculum()['nameDe']];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetParent(): Generator
    {
        include_once __DIR__ . '/../../Fixtures/study.php';
        $rootNode = RootNode::create(getRootNodeDataFromCurriculum());
        $childNode = $rootNode->getChildren()->first();

        yield 'root' => [$rootNode, null];
        yield 'child of root' => [$childNode, $rootNode];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetUri(): Generator
    {
        include_once __DIR__ . '/../../Fixtures/study.php';
        $rootNode = RootNode::create(getRootNodeDataFromCurriculum());

        yield [$rootNode, getRootNodeDataFromCurriculum()['uri']];
    }

    /**
     * @return Generator
     */
    public function provideForTestUpdateWithAddedChildOnSecondLevel(): Generator
    {
        include_once __DIR__ . '/../../Fixtures/study.php';
        $rootNode = RootNode::create(getRootNodeDataFromCurriculum());
        $rootNodeUpdate = RootNode::create(getRootNodeUpdateDataWithAddedChildOnSecondLevel());

        yield [$rootNode, $rootNodeUpdate];
    }

    /**
     * @return Generator
     */
    public function provideForTestUpdateWithIdenticalNodes(): Generator
    {
        include_once __DIR__ . '/../../Fixtures/study.php';
        $rootNode = RootNode::create(getRootNodeDataFromCurriculum());
        $rootNodeUpdate = RootNode::create(getRootNodeUpdateDataWithIdenticalNodes());

        yield [$rootNode, $rootNodeUpdate];
    }

    /**
     * @return Generator
     */
    public function provideForTestUpdateWithRemovedChildOnSecondLevel(): Generator
    {
        include_once __DIR__ . '/../../Fixtures/study.php';
        $rootNode = RootNode::create(getRootNodeDataFromCurriculum());
        $rootNodeUpdate = RootNode::create(getRootNodeUpdateDataWithRemovedChildOnSecondLevel());

        yield [$rootNode, $rootNodeUpdate];
    }

    /**
     * @return Generator
     */
    public function provideForTestUpdateWithWrongUpdateType(): Generator
    {
        include_once __DIR__ . '/../../Fixtures/study.php';
        $rootNode = RootNode::create(getRootNodeDataFromCurriculum());
        include_once __DIR__ . '/../../Fixtures/node/link_node.php';
        $nodeUpdate = LinkNode::create(getLinkNodeData());

        yield [$rootNode, $nodeUpdate];
    }

    /**
     * @dataProvider provideForTestBelongsToRootNode
     *
     * @param NodeInterface $leafNode
     * @param RootNode $rootNode
     * @param bool $expectedBelongsTo
     * @return void
     */
    public function testBelongsToRootNode(NodeInterface $leafNode, RootNode $rootNode, bool $expectedBelongsTo): void
    {
        $this->assertEquals($expectedBelongsTo, $leafNode->belongsToRootNode($rootNode));
    }

    /**
     * @dataProvider provideForTestCreateRootNode
     *
     * @param array<string, mixed> $rootNodeData
     * @return void
     */
    public function testCreateRootNode(array $rootNodeData): void
    {
        $rootNode = RootNode::create($rootNodeData);
        $this->assertInstanceOf(RootNode::class, $rootNode);
    }

    /**
     * @dataProvider provideForTestCreateWithInvalidNodeType
     *
     * @param array<string, mixed> $linkNodeData
     * @return void
     */
    public function testCreateWithInvalidNodeType(array $linkNodeData): void
    {
        $this->expectException(InvalidNodeTypeOnCreateException::class);
        RootNode::create($linkNodeData);
    }

    /**
     * @dataProvider provideForTestGetChildren
     *
     * @param RootNode $node
     * @param int $expectedChildrenCount
     * @return void
     */
    public function testGetChildren(RootNode $node, int $expectedChildrenCount): void
    {
        $this->assertEquals($expectedChildrenCount, $node->getChildren()->count());
    }

    /**
     * @dataProvider provideForTestGetId
     *
     * @param RootNode $node
     * @param int $expectedId
     * @return void
     */
    public function testGetId(RootNode $node, int $expectedId): void
    {
        $this->assertEquals($expectedId, $node->getId());
    }

    /**
     * @dataProvider provideForTestGetName
     *
     * @param RootNode $node
     * @param string $expectedName
     * @return void
     */
    public function testGetName(RootNode $node, string $expectedName): void
    {
        $this->assertEquals($expectedName, $node->getName());
    }

    /**
     * @dataProvider provideForTestGetParent
     *
     * @param NodeInterface $node
     * @param NodeInterface|null $expectedParent
     * @return void
     */
    public function testGetParent(NodeInterface $node, ?NodeInterface $expectedParent): void
    {
        $this->assertEquals($expectedParent, $node->getParent());
    }

    /**
     * @dataProvider provideForTestGetUri
     *
     * @param RootNode $node
     * @param string $expectedUri
     * @return void
     */
    public function testGetUri(RootNode $node, string $expectedUri): void
    {
        $this->assertEquals($expectedUri, $node->getUri());
    }

    /**
     * @dataProvider provideForTestUpdateWithAddedChildOnSecondLevel
     *
     * @param RootNode $rootNode
     * @param RootNode $rootNodeUpdate
     * @return void
     */
    public function testUpdateWithAddedChildOnSecondLevel(RootNode $rootNode, RootNode $rootNodeUpdate): void
    {
        // Ensure that the test does not run on nodes without children.
        if (0 === $rootNode->getChildren()->count()) {
            throw new LogicException('This test should not run on nodes without children as it\'s supposed to test deep updates.');
        }

        $firstLevelNode = $rootNode->getChildren()->first();
        $firstLevelNodeUpdate = $rootNodeUpdate->getChildren()->first();

        /** @phpstan-ignore-next-line */
        $this->assertNotEquals($firstLevelNodeUpdate->getChildren()->count(), $firstLevelNode->getChildren()->count());

        $rootNode->update($rootNodeUpdate);

        /** @phpstan-ignore-next-line */
        $this->assertEquals($firstLevelNodeUpdate->getChildren()->count(), $firstLevelNode->getChildren()->count());
        /** @phpstan-ignore-next-line */
        $this->assertInstanceOf(LinkNode::class, $firstLevelNode->getChildren()->last());
    }

    /**
     * @dataProvider provideForTestUpdateWithIdenticalNodes
     *
     * @param RootNode $rootNode
     * @param RootNode $rootNodeUpdate
     * @return void
     */
    public function testUpdateWithIdenticalNodes(RootNode $rootNode, RootNode $rootNodeUpdate): void
    {
        // Ensure that the test does not run on nodes without children.
        if (0 === $rootNode->getChildren()->count()) {
            throw new LogicException('This test should not run on nodes without children as it\'s supposed to test deep updates.');
        }

        $depth = 0;
        $leafNode = $rootNode;
        $leafNodeUpdate = $rootNodeUpdate;
        do {
            /** @phpstan-ignore-next-line */
            $this->assertNotEquals($leafNodeUpdate->getName(), $leafNode->getName());

            /** @phpstan-ignore-next-line */
            $leafNode = $leafNode->getChildren()->first();
            /** @phpstan-ignore-next-line */
            $leafNodeUpdate = $leafNodeUpdate->getChildren()->first();

            $depth++;

            /** @phpstan-ignore-next-line */
        } while (0 !== count($leafNode->getChildren()) && 0 !== count($leafNodeUpdate->getChildren()));

        $rootNode->update($rootNodeUpdate);

        $depthAfterUpdate = 0;
        $leafNode = $rootNode;
        $leafNodeUpdate = $rootNodeUpdate;
        do {
            /** @phpstan-ignore-next-line */
            $this->assertEquals($leafNodeUpdate->getName(), $leafNode->getName());

            /** @phpstan-ignore-next-line */
            $leafNode = $leafNode->getChildren()->first();
            /** @phpstan-ignore-next-line */
            $leafNodeUpdate = $leafNodeUpdate->getChildren()->first();

            $depthAfterUpdate++;

            /** @phpstan-ignore-next-line */
        } while (0 !== count($leafNode->getChildren()) && 0 !== count($leafNodeUpdate->getChildren()));

        $this->assertEquals($depthAfterUpdate, $depth);
    }

    /**
     * @dataProvider provideForTestUpdateWithRemovedChildOnSecondLevel
     *
     * @param RootNode $rootNode
     * @param RootNode $rootNodeUpdate
     * @return void
     */
    public function testUpdateWithRemovedChildOnSecondLevel(RootNode $rootNode, RootNode $rootNodeUpdate): void
    {
        // Ensure that the test does not run on nodes without children.
        if (0 === $rootNode->getChildren()->count()) {
            throw new LogicException('This test should not run on nodes without children as it\'s supposed to test deep updates.');
        }

        $firstLevelNode = $rootNode->getChildren()->first();
        $firstLevelNodeUpdate = $rootNodeUpdate->getChildren()->first();

        /** @phpstan-ignore-next-line */
        $this->assertNotEquals($firstLevelNodeUpdate->getChildren()->count(), $firstLevelNode->getChildren()->count());

        $rootNode->update($rootNodeUpdate);

        /** @phpstan-ignore-next-line */
        $this->assertEquals($firstLevelNodeUpdate->getChildren()->count(), $firstLevelNode->getChildren()->count());
    }

    /**
     * @dataProvider provideForTestUpdateWithWrongUpdateType
     *
     * @param RootNode $rootNode
     * @param LinkNode $nodeUpdate
     * @return void
     */
    public function testUpdateWithWrongUpdateType(RootNode $rootNode, LinkNode $nodeUpdate): void
    {
        $this->expectException(InvalidNodeTypeOnUpdateException::class);
        $rootNode->update($nodeUpdate);
    }
}
