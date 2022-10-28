<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage\Exception;

use ITB\CmlifeClient\Exception\StorageException;
use RuntimeException;

final class MoreThanOneSemesterIsCurrentException extends RuntimeException implements StorageException
{
    /**
     * @return MoreThanOneSemesterIsCurrentException
     */
    public static function create(): MoreThanOneSemesterIsCurrentException
    {
        return new self('The search for the current semester returned more than one semester. That should never happen.');
    }
}
