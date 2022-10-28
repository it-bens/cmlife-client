<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage\Exception;

use ITB\CmlifeClient\Exception\StorageException;
use RuntimeException;

final class NoPersonIsMeException extends RuntimeException implements StorageException
{
    /**
     * @return NoPersonIsMeException
     */
    public static function create(): NoPersonIsMeException
    {
        return new self('The search for the person that is me returned no person. That should never happen.');
    }
}
