<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage\Exception;

use ITB\CmlifeClient\Exception\StorageException;
use RuntimeException;

final class MoreThanOnePersonIsMeException extends RuntimeException implements StorageException
{
    /**
     * @return MoreThanOnePersonIsMeException
     */
    public static function create(): MoreThanOnePersonIsMeException
    {
        return new self('The search for the person that is me returned more than one person. That should never happen.');
    }
}
