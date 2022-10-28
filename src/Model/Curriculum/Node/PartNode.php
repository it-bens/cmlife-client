<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Model\Curriculum\Node;

use Doctrine\ORM\Mapping as ORM;
use ITB\CmlifeClient\Model\Curriculum\Node;
use ITB\CmlifeClient\Model\Curriculum\NodeInterface;

#[ORM\Entity]
final class PartNode extends Node
{
    public const TYPE = 'part';

    /**
     * @param array<string, mixed> $nodeData
     * @param NodeInterface|null $parent
     * @return PartNode
     */
    public static function create(array $nodeData, ?NodeInterface $parent = null): PartNode
    {
        /** @var PartNode $node */
        $node = parent::create($nodeData, $parent);

        return $node;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
