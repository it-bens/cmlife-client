<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Model\Curriculum;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ITB\CmlifeClient\Model\Curriculum\Node\RootNode;

#[ORM\Entity]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap(NodeFactory::NODE_TYPE_TO_CLASS_MAP)]
abstract class Node implements NodeInterface
{
    /**
     * @param int $id
     * @param string $uri
     * @param string|null $name
     * @param NodeInterface|null $parent
     * @param Collection<int, NodeInterface> $children
     */
    final public function __construct(
        #[ORM\Id]
        #[ORM\Column(name: 'id', type: 'integer')]
        protected readonly int $id,
        #[ORM\Column(name: 'uri', type: 'string')]
        protected readonly string $uri,
        #[ORM\Column(name: 'name', type: 'string', nullable: true)]
        protected readonly ?string $name,
        #[ORM\ManyToOne(targetEntity: Node::class, inversedBy: 'children')]
        #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id')]
        protected readonly ?NodeInterface $parent,
        #[ORM\OneToMany(targetEntity: Node::class, mappedBy: 'parent', cascade: ['all'])]
        protected Collection $children
    ) {
    }

    /**
     * @param array<string, mixed> $nodeData
     * @param NodeInterface|null $parent
     * @return NodeInterface
     */
    public static function create(array $nodeData, ?NodeInterface $parent = null): NodeInterface
    {
        $id = $nodeData['id'];
        $uri = $nodeData['uri'];

        $name = array_key_exists('nameDe', $nodeData) ? $nodeData['nameDe'] : null;

        $node = new static($id, $uri, $name, $parent, new ArrayCollection());
        foreach ($nodeData['children'] as $childNodeData) {
            $node->addChild(NodeFactory::createNode($childNodeData, $node));
        }

        return $node;
    }

    /**
     * @param NodeInterface $child
     * @return void
     */
    public function addChild(NodeInterface $child): void
    {
        $this->children->add($child);
    }

    /**
     * @param RootNode $rootNode
     * @return bool
     */
    public function belongsToRootNode(RootNode $rootNode): bool
    {
        if (null === $this->parent) {
            return false;
        }

        if ($rootNode->id === $this->parent->getId()) {
            return true;
        }

        return $this->parent->belongsToRootNode($rootNode);
    }

    /**
     * @return Collection<int, NodeInterface>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return NodeInterface|null
     */
    public function getParent(): ?NodeInterface
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}
