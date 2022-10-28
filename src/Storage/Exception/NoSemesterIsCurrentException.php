<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage\Exception;

use ITB\CmlifeClient\Exception\StorageException;
use RuntimeException;

final class NoSemesterIsCurrentException extends RuntimeException implements StorageException
{
    /**
     * @return NoSemesterIsCurrentException
     */
    public static function create(): NoSemesterIsCurrentException
    {
        return new self('The search for the current semester returned no semester. That should never happen.');
    }
}
