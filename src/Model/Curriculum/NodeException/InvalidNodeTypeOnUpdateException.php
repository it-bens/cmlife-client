<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Model\Curriculum\NodeException;

use RuntimeException;

final class InvalidNodeTypeOnUpdateException extends RuntimeException
{
    /**
     * @param string $actualNodeType
     * @param string $expectedNodeType
     * @return InvalidNodeTypeOnUpdateException
     */
    public static function create(string $actualNodeType, string $expectedNodeType): InvalidNodeTypeOnUpdateException
    {
        return new self(sprintf('The node to update is supposed to be of type %s, but it is of type %s.', $expectedNodeType, $actualNodeType));
    }
}
