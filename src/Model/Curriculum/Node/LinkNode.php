<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Model\Curriculum\Node;

use Doctrine\ORM\Mapping as ORM;
use ITB\CmlifeClient\Model\Curriculum\Node;
use ITB\CmlifeClient\Model\Curriculum\NodeInterface;

#[ORM\Entity]
final class LinkNode extends Node
{
    public const TYPE = 'link';

    #[ORM\Column(name: 'course_equivalence_uri', type: 'string', nullable: true)]
    private ?string $courseEquivalenceUri = null;

    /**
     * @param array<string, mixed> $nodeData
     * @param NodeInterface|null $parent
     * @return LinkNode
     */
    public static function create(array $nodeData, ?NodeInterface $parent = null): LinkNode
    {
        /** @var LinkNode $node */
        $node = parent::create($nodeData, $parent);
        $node->courseEquivalenceUri = $nodeData['courseEquivalenceUri'] ?? null;

        return $node;
    }

    /**
     * @return string|null
     */
    public function getCourseEquivalenceUri(): ?string
    {
        return $this->courseEquivalenceUri;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
