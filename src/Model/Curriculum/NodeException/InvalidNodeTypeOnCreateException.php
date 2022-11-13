<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Model\Curriculum\NodeException;

use RuntimeException;

final class InvalidNodeTypeOnCreateException extends RuntimeException
{
    /**
     * @param string $actualNodeType
     * @param string $expectedNodeType
     * @return InvalidNodeTypeOnCreateException
     */
    public static function create(string $actualNodeType, string $expectedNodeType): InvalidNodeTypeOnCreateException
    {
        return new self(sprintf('The created node is supposed to be of type %s, but it is of type %s.', $expectedNodeType, $actualNodeType));
    }
}
