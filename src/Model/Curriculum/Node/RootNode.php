<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Model\Curriculum\Node;

use Doctrine\ORM\Mapping as ORM;
use ITB\CmlifeClient\Model\Curriculum\Node;
use ITB\CmlifeClient\Model\Curriculum\NodeInterface;

#[ORM\Entity]
final class RootNode extends Node
{
    public const TYPE = 'root';

    /**
     * @param array<string, mixed> $nodeData
     * @param NodeInterface|null $parent
     * @return RootNode
     */
    public static function create(array $nodeData, ?NodeInterface $parent = null): RootNode
    {
        /** @var RootNode $node */
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
