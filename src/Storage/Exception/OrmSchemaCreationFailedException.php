<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage\Exception;

use Exception;
use ITB\CmlifeClient\Exception\StorageException;
use RuntimeException;

final class OrmSchemaCreationFailedException extends RuntimeException implements StorageException
{
    /**
     * @param Exception $exception
     * @return OrmSchemaCreationFailedException
     */
    public static function create(Exception $exception): OrmSchemaCreationFailedException
    {
        return new self('The creation or update of the doctrine ORM schema failed with an exception.', previous: $exception);
    }
}
