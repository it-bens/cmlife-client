<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage\Exception;

use Exception;
use ITB\CmlifeClient\Exception\StorageException;
use RuntimeException;

final class NodeSearchWithTypeAndStudyFailedException extends RuntimeException implements StorageException
{
    /**
     * @param Exception $exception
     * @return NodeSearchWithTypeAndStudyFailedException
     */
    public static function create(Exception $exception): NodeSearchWithTypeAndStudyFailedException
    {
        return new self('The node search with a node type for a specific study failed with an exception.', previous: $exception);
    }
}
