<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Model\Curriculum\NodeFactoryException;

use RuntimeException;

final class UnknownNodeTypException extends RuntimeException
{
    /**
     * @param string $nodeType
     * @param string[] $allowedNodeTypes
     * @return UnknownNodeTypException
     */
    public static function create(string $nodeType, array $allowedNodeTypes): UnknownNodeTypException
    {
        return new self(sprintf('The node %s is unknown. Known node types are [%s].', $nodeType, implode(', ', $allowedNodeTypes)));
    }
}
