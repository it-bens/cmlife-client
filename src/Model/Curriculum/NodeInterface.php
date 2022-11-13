<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Model\Curriculum;

use Doctrine\Common\Collections\Collection;
use ITB\CmlifeClient\Model\Curriculum\Node\RootNode;

interface NodeInterface
{
    /**
     * @param array<string, mixed> $nodeData
     * @param NodeInterface|null $parent
     * @return NodeInterface
     */
    public static function create(array $nodeData, ?NodeInterface $parent = null): NodeInterface;

    /**
     * @param RootNode $rootNode
     * @return bool
     */
    public function belongsToRootNode(RootNode $rootNode): bool;

    /**
     * @return Collection<int, NodeInterface>
     */
    public function getChildren(): Collection;

    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return NodeInterface|null
     */
    public function getParent(): ?NodeInterface;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getUri(): string;

    /**
     * @param NodeInterface $node
     * @return void
     */
    public function update(NodeInterface $node): void;
}
